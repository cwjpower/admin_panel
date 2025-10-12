// lib/services/api_service.dart
import 'dart:convert';
import 'package:http/http.dart' as http;
import '../models/series.dart';
import '../models/volume.dart';

class ApiService {
  static const String baseUrl = 'http://34.64.84.117:8081/admin/apis';

  // ============================================
  // 로그인 관련 API
  // ============================================

  /// 로그인
  static Future<Map<String, dynamic>> login({
    required String email,
    required String password,
  }) async {
    try {
      final url = '$baseUrl/users/user_login_clean.php';
      print('Login attempt: $url');

      final response = await http.post(
        Uri.parse(url),
        body: {
          'user_login': email,
          'user_pass': password,
        },
      );

      print('Login response status: ${response.statusCode}');
      print('Login response body: ${response.body}');

      if (response.statusCode == 200) {
        final jsonData = json.decode(response.body);

        if (jsonData['code'] == 0) {
          // 로그인 성공
          return {
            'success': true,
            'token': jsonData['token'],
            'uid': jsonData['uid'],
            'user_name': jsonData['user_name'],
            'display_name': jsonData['display_name'],
            'user_level': jsonData['user_level'],
          };
        } else {
          // 로그인 실패
          return {
            'success': false,
            'message': jsonData['msg'] ?? 'Login failed'
          };
        }
      } else {
        return {
          'success': false,
          'message': 'Server error: ${response.statusCode}'
        };
      }
    } catch (e) {
      print('Login error: $e');
      return {
        'success': false,
        'message': 'Network error: $e'
      };
    }
  }

  // ============================================
  // 시리즈 관련 API
  // ============================================

  /// 시리즈 목록 조회
  /// category: 'all', 'marvel', 'dc', 'image' 등
  static Future<Map<String, dynamic>> fetchSeriesList({
    String category = 'all',
    int page = 1,
    int limit = 20,
  }) async {
    try {
      final url = '$baseUrl/books/series_list.php?category=$category&page=$page&limit=$limit';
      print('Fetching series list: $url');

      final response = await http.get(Uri.parse(url));

      print('Series list response status: ${response.statusCode}');

      if (response.statusCode == 200) {
        final jsonData = json.decode(response.body);

        if (jsonData['code'] == 0) {
          final List<dynamic> seriesJson = jsonData['data']['series'];
          final List<Series> seriesList = seriesJson
              .map((json) => Series.fromJson(json))
              .toList();

          print('Loaded ${seriesList.length} series');

          return {
            'success': true,
            'series': seriesList,
            'pagination': jsonData['data']['pagination'],
          };
        } else {
          return {
            'success': false,
            'message': jsonData['msg'] ?? 'Unknown error'
          };
        }
      } else {
        return {
          'success': false,
          'message': 'Server error: ${response.statusCode}'
        };
      }
    } catch (e) {
      print('Error fetching series list: $e');
      return {
        'success': false,
        'message': 'Network error: $e'
      };
    }
  }

  /// 시리즈 상세 조회 (권 목록 포함)
  static Future<Map<String, dynamic>> fetchSeriesDetail(int seriesId) async {
    try {
      final url = '$baseUrl/books/series_detail.php?series_id=$seriesId';
      print('Fetching series detail: $url');

      final response = await http.get(Uri.parse(url));

      print('Series detail response status: ${response.statusCode}');

      if (response.statusCode == 200) {
        final jsonData = json.decode(response.body);

        if (jsonData['code'] == 0) {
          final Series series = Series.fromJson(jsonData['data']['series']);

          final List<dynamic> volumesJson = jsonData['data']['volumes'];
          final List<Volume> volumes = volumesJson
              .map((json) => Volume.fromJson(json))
              .toList();

          print('Loaded series: ${series.seriesName} with ${volumes.length} volumes');

          return {
            'success': true,
            'series': series,
            'volumes': volumes,
            'stats': jsonData['data']['stats'],
          };
        } else {
          return {
            'success': false,
            'message': jsonData['msg'] ?? 'Unknown error'
          };
        }
      } else {
        return {
          'success': false,
          'message': 'Server error: ${response.statusCode}'
        };
      }
    } catch (e) {
      print('Error fetching series detail: $e');
      return {
        'success': false,
        'message': 'Network error: $e'
      };
    }
  }

  /// 권 상세 조회
  static Future<Map<String, dynamic>> fetchVolumeDetail(int volumeId) async {
    try {
      final url = '$baseUrl/books/volume_detail.php?volume_id=$volumeId';
      print('Fetching volume detail: $url');

      final response = await http.get(Uri.parse(url));

      print('Volume detail response status: ${response.statusCode}');

      if (response.statusCode == 200) {
        final jsonData = json.decode(response.body);

        if (jsonData['code'] == 0) {
          final Volume volume = Volume.fromJson(jsonData['data']['volume']);

          print('Loaded volume: ${volume.volumeTitle}');

          return {
            'success': true,
            'volume': volume,
          };
        } else {
          return {
            'success': false,
            'message': jsonData['msg'] ?? 'Unknown error'
          };
        }
      } else {
        return {
          'success': false,
          'message': 'Server error: ${response.statusCode}'
        };
      }
    } catch (e) {
      print('Error fetching volume detail: $e');
      return {
        'success': false,
        'message': 'Network error: $e'
      };
    }
  }

  // ============================================
  // 기타 유틸리티
  // ============================================

  /// 가격 포맷팅
  static String formatPrice(int price) {
    return '₩${price.toString().replaceAllMapped(
        RegExp(r'(\d{1,3})(?=(\d{3})+(?!\d))'),
            (Match m) => '${m[1]},'
    )}';
  }
}