import 'dart:convert';
import 'package:http/http.dart' as http;
import '../models/series.dart';
import '../models/volume.dart';

class ApiService {
  static const String baseUrl = 'http://34.64.84.117:8081/admin/apis';

  // 로그인
  static Future<Map<String, dynamic>> login(String email, String password) async {
    print('Login attempt - Email: $email');
    try {
      final url = '$baseUrl/users/user_login_clean.php';
      final response = await http.post(
        Uri.parse(url),
        body: {
          'user_login': email,
          'user_pass': password,
        },
      );
      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (data['code'] == 0) {
          return {
            'success': true,
            'token': data['token'],
            'uid': data['uid'],
            'user_name': data['user_name'],
            'display_name': data['display_name'],
            'user_level': data['user_level'],
            'profile_avatar': data['profile_avatar'],
          };
        } else {
          return {
            'success': false,
            'code': data['code'],
            'message': data['msg'] ?? '로그인에 실패했습니다.',
          };
        }
      } else {
        return {
          'success': false,
          'message': '서버 오류가 발생했습니다.',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': '네트워크 오류가 발생했습니다.',
      };
    }
  }

  // 시리즈 목록 조회
  static Future<Map<String, dynamic>> fetchSeriesList({
    String category = 'all',
    int page = 1,
    int limit = 20,
  }) async {
    try {
      final url = '$baseUrl/books/series_list.php?category=$category&page=$page&limit=$limit';
      print('Fetching series list: $url');

      final response = await http.get(Uri.parse(url));

      if (response.statusCode == 200) {
        final jsonData = json.decode(response.body);

        if (jsonData['code'] == 0) {
          final List<dynamic> seriesJson = jsonData['data']['series'];
          final List<Series> seriesList = seriesJson
              .map((json) => Series.fromJson(json))
              .toList();

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

  // 시리즈 상세 조회
  static Future<Map<String, dynamic>> fetchSeriesDetail(int seriesId) async {
    try {
      final url = '$baseUrl/books/series_detail.php?series_id=$seriesId';
      print('Fetching series detail: $url');

      final response = await http.get(Uri.parse(url));

      if (response.statusCode == 200) {
        final jsonData = json.decode(response.body);

        if (jsonData['code'] == 0) {
          final Series series = Series.fromJson(jsonData['data']['series']);

          final List<dynamic> volumesJson = jsonData['data']['volumes'];
          final List<Volume> volumes = volumesJson
              .map((json) => Volume.fromJson(json))
              .toList();

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

  // 기존 메서드들 (하위 호환성)
  static Future<List<dynamic>> getBooks({String? category}) async {
    try {
      String url = '$baseUrl/books/list.php';
      if (category != null && category.isNotEmpty) {
        url += '?category=$category';
      }
      final response = await http.get(Uri.parse(url));
      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (data['code'] == 0) {
          return data['books'] ?? [];
        }
      }
      return [];
    } catch (e) {
      return [];
    }
  }

  static Future<List<dynamic>> getPosts({String? category}) async {
    try {
      String url = '$baseUrl/posts/list.php';
      if (category != null && category.isNotEmpty) {
        url += '?category=$category';
      }
      final response = await http.get(Uri.parse(url));
      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (data['code'] == 0) {
          return data['posts'] ?? [];
        }
      }
      return [];
    } catch (e) {
      return [];
    }
  }

  static Future<List<dynamic>> getBanners() async {
    try {
      String url = '$baseUrl/banners/list.php';
      final response = await http.get(Uri.parse(url));
      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (data['code'] == 0) {
          return data['banners'] ?? [];
        }
      }
      return [];
    } catch (e) {
      return [];
    }
  }
}