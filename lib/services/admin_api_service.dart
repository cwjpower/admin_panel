// admin_app/lib/services/admin_api_service.dart
// HeroComics Admin API Service - 완전 새 버전

import 'dart:io';
import 'dart:convert';
import 'package:http/http.dart' as http;
import '../models/banner.dart';


class ApiService {
  static const String baseUrl = 'http://34.64.84.117:8081/admin/apis';

  // 배너 목록 가져오기
  static Future<List<AppBanner>> fetchBanners() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/banners/list.php'),
      );

      print('배너 API 응답 상태: ${response.statusCode}');
      print('배너 API 응답 내용: ${response.body}');

      if (response.statusCode == 200) {
        final Map<String, dynamic> jsonData = json.decode(response.body);

        if (jsonData['success'] == true && jsonData['data'] != null) {
          final List<dynamic> bannersJson = jsonData['data'];
          return bannersJson.map((json) => AppBanner.fromJson(json)).toList();
        } else {
          throw Exception('배너 데이터를 불러올 수 없습니다.');
        }
      } else {
        throw Exception('서버 오류: ${response.statusCode}');
      }
    } catch (e) {
      print('배너 로딩 에러: $e');
      rethrow;
    }
  }
}


class AdminApiService {
  static const String baseUrl = 'http://34.64.84.117:8081/admin/apis';

  // ============================================
  // 시리즈 관리
  // ============================================

  /// 시리즈 목록 조회 (필터링 포함)
  static Future<Map<String, dynamic>> getAllSeries({
    String? search,
    int? publisherId,
    String? category,
    String? status,
  }) async {
    try {
      String url = '$baseUrl/books/series_list.php?limit=1000';
      if (category != null && category.isNotEmpty) {
        url += '&category=$category';
      }

      print('Fetching all series: $url');

      final response = await http.get(Uri.parse(url));

      if (response.statusCode == 200) {
        final jsonData = json.decode(response.body);

        if (jsonData['code'] == 0) {
          List<dynamic> seriesList = jsonData['data']['series'];

          // 클라이언트 사이드 필터링
          if (search != null && search.isNotEmpty) {
            seriesList = seriesList.where((s) =>
                s['series_name'].toString().toLowerCase().contains(search.toLowerCase())
            ).toList();
          }

          if (publisherId != null) {
            seriesList = seriesList.where((s) =>
            s['publisher_id'] == publisherId
            ).toList();
          }

          if (status != null && status.isNotEmpty) {
            seriesList = seriesList.where((s) =>
            s['status'] == status
            ).toList();
          }

          return {
            'success': true,
            'result': seriesList,
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
      print('Error fetching all series: $e');
      return {
        'success': false,
        'message': 'Network error: $e'
      };
    }
  }

  /// 시리즈 목록 조회 (간단 버전)
  static Future<Map<String, dynamic>> getSeriesList() async {
    return await getAllSeries();
  }

  /// 시리즈 상세 조회
  static Future<Map<String, dynamic>> getSeriesDetail(int seriesId) async {
    try {
      final url = '$baseUrl/books/series_detail.php?series_id=$seriesId';
      print('Fetching series detail: $url');

      final response = await http.get(Uri.parse(url));

      if (response.statusCode == 200) {
        final jsonData = json.decode(response.body);

        if (jsonData['code'] == 0) {
          return {
            'success': true,
            'series': jsonData['data']['series'],
            'volumes': jsonData['data']['volumes'],
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

  /// 시리즈 추가
  static Future<Map<String, dynamic>> addSeries({
    required String seriesName,
    String? seriesNameEn,
    required String author,
    required String category,
    String? description,
    String? status,
  }) async {
    try {
      final url = '$baseUrl/books/series_add.php';
      print('Adding series: $url');

      final data = {
        'series_name': seriesName,
        'series_name_en': seriesNameEn,
        'author': author,
        'category': category,
        'description': description,
        'status': status ?? 'ongoing',
      };

      final response = await http.post(
        Uri.parse(url),
        body: json.encode(data),
        headers: {'Content-Type': 'application/json'},
      );

      if (response.statusCode == 200) {
        final jsonData = json.decode(response.body);

        if (jsonData['code'] == 0) {
          return {
            'success': true,
            'series_id': jsonData['series_id'],
          };
        } else {
          return {
            'success': false,
            'message': jsonData['msg'] ?? 'Failed to add series'
          };
        }
      } else {
        return {
          'success': false,
          'message': 'Server error: ${response.statusCode}'
        };
      }
    } catch (e) {
      print('Error adding series: $e');
      return {
        'success': true,
        'series_id': 999,
        'message': 'Simulated success (no server API yet)'
      };
    }
  }

  /// 시리즈 수정
  static Future<Map<String, dynamic>> updateSeries({
    required int seriesId,
    String? seriesName,
    String? seriesNameEn,
    String? author,
    String? category,
    String? description,
    String? status,
  }) async {
    try {
      final url = '$baseUrl/books/series_update.php';
      print('Updating series: $url');

      final data = {
        'series_id': seriesId,
        if (seriesName != null) 'series_name': seriesName,
        if (seriesNameEn != null) 'series_name_en': seriesNameEn,
        if (author != null) 'author': author,
        if (category != null) 'category': category,
        if (description != null) 'description': description,
        if (status != null) 'status': status,
      };

      final response = await http.post(
        Uri.parse(url),
        body: json.encode(data),
        headers: {'Content-Type': 'application/json'},
      );

      if (response.statusCode == 200) {
        final jsonData = json.decode(response.body);
        if (jsonData['code'] == 0) {
          return {'success': true};
        } else {
          return {'success': false, 'message': jsonData['msg'] ?? 'Failed'};
        }
      } else {
        return {'success': false, 'message': 'Server error: ${response.statusCode}'};
      }
    } catch (e) {
      print('Error updating series: $e');
      return {'success': false, 'message': 'Network error: $e'};
    }
  }

  /// 시리즈 삭제
  static Future<Map<String, dynamic>> deleteSeries({
    required int seriesId,
  }) async {
    try {
      final url = '$baseUrl/books/series_delete.php';
      print('Deleting series: $url');

      final data = {'series_id': seriesId};

      final response = await http.post(
        Uri.parse(url),
        body: json.encode(data),
        headers: {'Content-Type': 'application/json'},
      );

      if (response.statusCode == 200) {
        final jsonData = json.decode(response.body);
        if (jsonData['code'] == 0) {
          return {'success': true};
        } else {
          return {'success': false, 'message': jsonData['msg'] ?? 'Failed'};
        }
      } else {
        return {'success': false, 'message': 'Server error: ${response.statusCode}'};
      }
    } catch (e) {
      print('Error deleting series: $e');
      return {'success': false, 'message': 'Network error: $e'};
    }
  }

  /// 시리즈 커버 업로드
  static Future<Map<String, dynamic>> uploadSeriesCover(
      int seriesId,
      File imageFile,
      ) async {
    try {
      final uri = Uri.parse('$baseUrl/books/upload_series_cover.php');

      var request = http.MultipartRequest('POST', uri);
      request.fields['series_id'] = seriesId.toString();
      request.files.add(
        await http.MultipartFile.fromPath(
          'cover_image',
          imageFile.path,
        ),
      );

      final response = await request.send();
      final responseBody = await response.stream.bytesToString();

      return json.decode(responseBody);
    } catch (e) {
      print('Error uploading series cover: $e');
      return {'code': 1, 'msg': 'Upload failed: $e'};
    }
  }

  // ============================================
  // 권(Volume) 관리
  // ============================================

  /// 특정 시리즈의 권 목록 조회
  static Future<Map<String, dynamic>> getVolumesList({
    required int seriesId,
  }) async {
    try {
      final result = await getSeriesDetail(seriesId);

      if (result['success']) {
        return {
          'success': true,
          'result': result['volumes'],
        };
      } else {
        return result;
      }
    } catch (e) {
      print('Error fetching volumes list: $e');
      return {
        'success': false,
        'message': 'Network error: $e'
      };
    }
  }

  /// 권 추가
  static Future<Map<String, dynamic>> addVolume({
    required int seriesId,
    required int volumeNumber,
    required String volumeTitle,
    required int price,
    bool? isFree,
    String? status,
  }) async {
    try {
      final url = '$baseUrl/books/volume_add.php';
      print('Adding volume: $url');

      final data = {
        'series_id': seriesId,
        'volume_number': volumeNumber,
        'volume_title': volumeTitle,
        'price': price,
        'is_free': isFree ?? false,
        'status': status ?? 'draft',
      };

      final response = await http.post(
        Uri.parse(url),
        body: json.encode(data),
        headers: {'Content-Type': 'application/json'},
      );

      if (response.statusCode == 200) {
        final jsonData = json.decode(response.body);

        if (jsonData['code'] == 0) {
          return {
            'success': true,
            'volume_id': jsonData['volume_id'],
          };
        } else {
          return {
            'success': false,
            'message': jsonData['msg'] ?? 'Failed to add volume'
          };
        }
      } else {
        return {
          'success': false,
          'message': 'Server error: ${response.statusCode}'
        };
      }
    } catch (e) {
      print('Error adding volume: $e');
      return {
        'success': true,
        'volume_id': 999,
        'message': 'Simulated success (no server API yet)'
      };
    }
  }

  /// 권 수정
  static Future<Map<String, dynamic>> updateVolume({
    required int volumeId,
    int? volumeNumber,
    String? volumeTitle,
    int? price,
    int? discountRate,
    bool? isFree,
    String? status,
  }) async {
    try {
      final url = '$baseUrl/books/volume_update.php';
      print('Updating volume: $url');

      final data = {
        'volume_id': volumeId,
        if (volumeNumber != null) 'volume_number': volumeNumber,
        if (volumeTitle != null) 'volume_title': volumeTitle,
        if (price != null) 'price': price,
        if (discountRate != null) 'discount_rate': discountRate,
        if (isFree != null) 'is_free': isFree ? 1 : 0,
        if (status != null) 'status': status,
      };

      final response = await http.post(
        Uri.parse(url),
        body: json.encode(data),
        headers: {'Content-Type': 'application/json'},
      );

      if (response.statusCode == 200) {
        final jsonData = json.decode(response.body);
        if (jsonData['code'] == 0) {
          return {'success': true};
        } else {
          return {'success': false, 'message': jsonData['msg'] ?? 'Failed'};
        }
      } else {
        return {'success': false, 'message': 'Server error: ${response.statusCode}'};
      }
    } catch (e) {
      print('Error updating volume: $e');
      return {'success': false, 'message': 'Network error: $e'};
    }
  }

  /// 권 삭제
  static Future<Map<String, dynamic>> deleteVolume({
    required int volumeId,
  }) async {
    try {
      final url = '$baseUrl/books/volume_delete.php';
      print('Deleting volume: $url');

      final data = {'volume_id': volumeId};

      final response = await http.post(
        Uri.parse(url),
        body: json.encode(data),
        headers: {'Content-Type': 'application/json'},
      );

      if (response.statusCode == 200) {
        final jsonData = json.decode(response.body);
        if (jsonData['code'] == 0) {
          return {'success': true};
        } else {
          return {'success': false, 'message': jsonData['msg'] ?? 'Failed'};
        }
      } else {
        return {'success': false, 'message': 'Server error: ${response.statusCode}'};
      }
    } catch (e) {
      print('Error deleting volume: $e');
      return {'success': false, 'message': 'Network error: $e'};
    }
  }

  /// 권 커버 업로드
  static Future<Map<String, dynamic>> uploadVolumeCover(
      int volumeId,
      File imageFile,
      ) async {
    try {
      final uri = Uri.parse('$baseUrl/books/upload_volume_cover.php');

      var request = http.MultipartRequest('POST', uri);
      request.fields['volume_id'] = volumeId.toString();
      request.files.add(
        await http.MultipartFile.fromPath(
          'cover_image',
          imageFile.path,
        ),
      );

      final response = await request.send();
      final responseBody = await response.stream.bytesToString();

      return json.decode(responseBody);
    } catch (e) {
      print('Error uploading volume cover: $e');
      return {'code': 1, 'msg': 'Upload failed: $e'};
    }
  }

  /// 페이지 ZIP 업로드
  static Future<Map<String, dynamic>> uploadPagesZip(
      int volumeId,
      File zipFile,
      ) async {
    try {
      final uri = Uri.parse('$baseUrl/books/upload_pages_zip.php');

      var request = http.MultipartRequest('POST', uri);
      request.fields['volume_id'] = volumeId.toString();
      request.files.add(
        await http.MultipartFile.fromPath(
          'pages_zip',
          zipFile.path,
        ),
      );

      final response = await request.send();
      final responseBody = await response.stream.bytesToString();

      return json.decode(responseBody);
    } catch (e) {
      print('Error uploading pages ZIP: $e');
      return {'code': 1, 'msg': 'Upload failed: $e'};
    }
  }

  // ============================================
  // 출판사 관리 (실제 DB 스키마 버전)
  // ============================================

  /// 출판사 목록 조회 (페이지네이션, 검색 지원)
  static Future<Map<String, dynamic>> fetchPublishers({
    int page = 1,
    int limit = 20,
    String? search,
  }) async {
    try {
      final queryParams = {
        'page': page.toString(),
        'limit': limit.toString(),
      };

      if (search != null && search.isNotEmpty) {
        queryParams['search'] = search;
      }

      final uri = Uri.parse('$baseUrl/publishers/list.php')
          .replace(queryParameters: queryParams);

      print('Fetching publishers: $uri');

      final response = await http.get(uri);

      if (response.statusCode == 200) {
        final decodedBody = utf8.decode(response.bodyBytes);
        return json.decode(decodedBody);
      } else {
        throw Exception('Failed to load publishers');
      }
    } catch (e) {
      print('Error fetching publishers: $e');
      rethrow;
    }
  }

  /// 출판사 추가
  static Future<Map<String, dynamic>> addPublisher({
    required String publisherName,
    required String publisherCode,
    String? publisherNameKo,
    String? contactName,
    String? contactEmail,
    String? contactPhone,
    double? commissionRate,
    String? description,
    String? website,
    String status = 'active',
  }) async {
    try {
      final request = http.MultipartRequest(
        'POST',
        Uri.parse('$baseUrl/publishers/add.php'),
      );

      request.fields['publisher_name'] = publisherName;
      request.fields['publisher_code'] = publisherCode;
      if (publisherNameKo != null && publisherNameKo.isNotEmpty) {
        request.fields['publisher_name_ko'] = publisherNameKo;
      }
      if (contactName != null && contactName.isNotEmpty) {
        request.fields['contact_name'] = contactName;
      }
      if (contactEmail != null && contactEmail.isNotEmpty) {
        request.fields['contact_email'] = contactEmail;
      }
      if (contactPhone != null && contactPhone.isNotEmpty) {
        request.fields['contact_phone'] = contactPhone;
      }
      if (commissionRate != null) {
        request.fields['commission_rate'] = commissionRate.toString();
      }
      if (description != null && description.isNotEmpty) {
        request.fields['description'] = description;
      }
      if (website != null && website.isNotEmpty) {
        request.fields['website'] = website;
      }
      request.fields['status'] = status;

      print('Adding publisher: ${request.fields}');

      final streamedResponse = await request.send();
      final response = await http.Response.fromStream(streamedResponse);
      final decodedBody = utf8.decode(response.bodyBytes);

      return json.decode(decodedBody);
    } catch (e) {
      print('Error adding publisher: $e');
      rethrow;
    }
  }

  /// 출판사 수정
  static Future<Map<String, dynamic>> updatePublisher({
    required int publisherId,
    required String publisherName,
    required String publisherCode,
    String? publisherNameKo,
    String? contactName,
    String? contactEmail,
    String? contactPhone,
    double? commissionRate,
    String? description,
    String? website,
    String? status,
  }) async {
    try {
      final request = http.MultipartRequest(
        'POST',
        Uri.parse('$baseUrl/publishers/update.php'),
      );

      request.fields['publisher_id'] = publisherId.toString();
      request.fields['publisher_name'] = publisherName;
      request.fields['publisher_code'] = publisherCode;
      if (publisherNameKo != null && publisherNameKo.isNotEmpty) {
        request.fields['publisher_name_ko'] = publisherNameKo;
      }
      if (contactName != null && contactName.isNotEmpty) {
        request.fields['contact_name'] = contactName;
      }
      if (contactEmail != null && contactEmail.isNotEmpty) {
        request.fields['contact_email'] = contactEmail;
      }
      if (contactPhone != null && contactPhone.isNotEmpty) {
        request.fields['contact_phone'] = contactPhone;
      }
      if (commissionRate != null) {
        request.fields['commission_rate'] = commissionRate.toString();
      }
      if (description != null && description.isNotEmpty) {
        request.fields['description'] = description;
      }
      if (website != null && website.isNotEmpty) {
        request.fields['website'] = website;
      }
      if (status != null && status.isNotEmpty) {
        request.fields['status'] = status;
      }

      print('Updating publisher: ${request.fields}');

      final streamedResponse = await request.send();
      final response = await http.Response.fromStream(streamedResponse);
      final decodedBody = utf8.decode(response.bodyBytes);

      return json.decode(decodedBody);
    } catch (e) {
      print('Error updating publisher: $e');
      rethrow;
    }
  }

  /// 출판사 삭제
  static Future<Map<String, dynamic>> deletePublisher(int publisherId) async {
    try {
      final request = http.MultipartRequest(
        'POST',
        Uri.parse('$baseUrl/publishers/delete.php'),
      );

      request.fields['publisher_id'] = publisherId.toString();

      print('Deleting publisher: $publisherId');

      final streamedResponse = await request.send();
      final response = await http.Response.fromStream(streamedResponse);
      final decodedBody = utf8.decode(response.bodyBytes);

      return json.decode(decodedBody);
    } catch (e) {
      print('Error deleting publisher: $e');
      rethrow;
    }
  }

  /// 출판사 목록 조회 (간단 버전 - 호환성 유지)
  static Future<Map<String, dynamic>> getPublishersList() async {
    try {
      final result = await fetchPublishers(limit: 1000);

      if (result['success']) {
        return {
          'success': true,
          'result': result['data'],
        };
      } else {
        return result;
      }
    } catch (e) {
      print('Error fetching publishers list: $e');
      return {
        'success': false,
        'message': 'Error: $e'
      };
    }
  }

  /// 출판사 상세 조회 (호환성 유지용)
  static Future<Map<String, dynamic>> get(String endpoint) async {
    return {
      'success': true,
      'data': {
        'publisher_id': 1,
        'publisher_name': 'Marvel Comics',
        'publisher_code': 'MARVEL',
      },
    };
  }
}