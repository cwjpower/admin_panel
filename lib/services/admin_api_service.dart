import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class AdminApiService {
  // 서버 주소
  static const String baseUrl = 'http://34.64.84.117:8081/admin/apis';

  /// 어드민 로그인
  static Future<Map<String, dynamic>> login(String email, String password) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/users/admin_login.php'),
        body: {
          'email': email,
          'password': password,
        },
      );

      print('Login Response Status: ${response.statusCode}');
      print('Login Response Body: ${response.body}');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);

        if (data['code'] == 0) {
          // 로그인 성공 - 토큰과 어드민 정보 저장
          await _saveToken(data['token']);
          await _saveAdminInfo(data['admin']);
        }

        return data;
      } else {
        return {
          'code': 1,
          'msg': '서버 오류: ${response.statusCode}',
        };
      }
    } catch (e) {
      print('Login Error: $e');
      return {
        'code': 1,
        'msg': '네트워크 오류: $e',
      };
    }
  }

  /// 토큰 저장
  static Future<void> _saveToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('admin_token', token);
    print('Token saved: $token');
  }

  /// 어드민 정보 저장 (권한 시스템 포함)
  static Future<void> _saveAdminInfo(Map<String, dynamic> admin) async {
    final prefs = await SharedPreferences.getInstance();

    // 기본 정보
    await prefs.setString('admin_uid', admin['uid'].toString());
    await prefs.setString('admin_email', admin['email'] ?? '');
    await prefs.setString('admin_name', admin['name'] ?? '');
    await prefs.setString('admin_level', admin['level'].toString());
    await prefs.setString('admin_permission', admin['permission'] ?? '');
    await prefs.setString('admin_role', admin['role'] ?? '');

    // 출판사 정보 (출판사 어드민인 경우)
    if (admin['publisher'] != null) {
      await prefs.setString('publisher_id', admin['publisher']['id'].toString());
      await prefs.setString('publisher_name', admin['publisher']['name'] ?? '');
      await prefs.setString('publisher_code', admin['publisher']['code'] ?? '');
      print('Publisher info saved: ${admin['publisher']}');
    } else {
      // 슈퍼 어드민이면 출판사 정보 삭제
      await prefs.remove('publisher_id');
      await prefs.remove('publisher_name');
      await prefs.remove('publisher_code');
    }

    print('Admin info saved: $admin');
  }

  /// 토큰 가져오기
  static Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('admin_token');
  }

  /// 어드민 정보 가져오기 (권한 포함)
  static Future<Map<String, String?>> getAdminInfo() async {
    final prefs = await SharedPreferences.getInstance();
    return {
      'uid': prefs.getString('admin_uid'),
      'email': prefs.getString('admin_email'),
      'name': prefs.getString('admin_name'),
      'level': prefs.getString('admin_level'),
      'permission': prefs.getString('admin_permission'),
      'role': prefs.getString('admin_role'),
      'publisher_id': prefs.getString('publisher_id'),
      'publisher_name': prefs.getString('publisher_name'),
      'publisher_code': prefs.getString('publisher_code'),
    };
  }

  /// 권한 확인
  static Future<bool> isSuperAdmin() async {
    final info = await getAdminInfo();
    return info['permission'] == 'super_admin';
  }

  /// 출판사 어드민 여부 확인
  static Future<bool> isPublisherAdmin() async {
    final info = await getAdminInfo();
    return info['permission'] == 'publisher_admin';
  }

  /// 출판사 ID 가져오기
  static Future<int?> getPublisherId() async {
    final info = await getAdminInfo();
    final publisherId = info['publisher_id'];
    return publisherId != null ? int.tryParse(publisherId) : null;
  }

  /// 로그인 여부 확인
  static Future<bool> isLoggedIn() async {
    final token = await getToken();
    return token != null && token.isNotEmpty;
  }

  /// 로그아웃 (모든 정보 삭제)
  static Future<void> logout() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('admin_token');
    await prefs.remove('admin_uid');
    await prefs.remove('admin_email');
    await prefs.remove('admin_name');
    await prefs.remove('admin_level');
    await prefs.remove('admin_permission');
    await prefs.remove('admin_role');
    await prefs.remove('publisher_id');
    await prefs.remove('publisher_name');
    await prefs.remove('publisher_code');
    print('Logged out - all data cleared');
  }

  /// API 요청 헤더 (토큰 포함)
  static Future<Map<String, String>> getHeaders() async {
    final token = await getToken();
    return {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer ${token ?? ''}',
    };
  }

  /// GET 요청 (공통)
  static Future<Map<String, dynamic>> get(String endpoint) async {
    try {
      final headers = await getHeaders();
      final response = await http.get(
        Uri.parse('$baseUrl/$endpoint'),
        headers: headers,
      );

      print('GET $endpoint - Status: ${response.statusCode}');
      print('Response: ${response.body}');

      if (response.statusCode == 200) {
        return json.decode(response.body);
      } else {
        return {
          'code': 1,
          'msg': '서버 오류: ${response.statusCode}',
        };
      }
    } catch (e) {
      print('GET Error: $e');
      return {
        'code': 1,
        'msg': '네트워크 오류: $e',
      };
    }
  }

  /// POST 요청 (공통)
  static Future<Map<String, dynamic>> post(
      String endpoint,
      Map<String, dynamic> data,
      ) async {
    try {
      final token = await getToken();
      final response = await http.post(
        Uri.parse('$baseUrl/$endpoint'),
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'Authorization': 'Bearer ${token ?? ''}',
        },
        body: data,
      );

      print('POST $endpoint - Status: ${response.statusCode}');
      print('Response: ${response.body}');

      if (response.statusCode == 200) {
        return json.decode(response.body);
      } else {
        return {
          'code': 1,
          'msg': '서버 오류: ${response.statusCode}',
        };
      }
    } catch (e) {
      print('POST Error: $e');
      return {
        'code': 1,
        'msg': '네트워크 오류: $e',
      };
    }
  }

  // ========== 대시보드 API ==========

  /// 대시보드 통계 조회
  static Future<Map<String, dynamic>> getDashboardStats() async {
    return await get('admin/dashboard.php');
  }

  // ========== 출판사 관리 API (슈퍼 어드민 전용) ==========

  /// 출판사 목록 조회
  static Future<Map<String, dynamic>> getPublishersList() async {
    return await get('publishers/list.php');
  }

  /// 출판사 승인
  static Future<Map<String, dynamic>> approvePublisher(int publisherId) async {
    return await post('publishers/approve.php', {'publisher_id': publisherId.toString()});
  }

  // ========== 책 관리 API ==========

  /// 책 목록 조회
  static Future<Map<String, dynamic>> getBooksList({
    int page = 1,
    int limit = 20,
  }) async {
    return await get('books/admin_list.php?page=$page&limit=$limit');
  }

  /// 책 추가
  static Future<Map<String, dynamic>> addBook(Map<String, dynamic> bookData) async {
    return await post('books/add.php', bookData);
  }

  /// 책 수정
  static Future<Map<String, dynamic>> updateBook(String bookId, Map<String, dynamic> bookData) async {
    bookData['book_id'] = bookId;
    return await post('books/update.php', bookData);
  }

  /// 책 삭제
  static Future<Map<String, dynamic>> deleteBook(String bookId) async {
    return await post('books/delete.php', {'book_id': bookId});
  }

  // ========== 회원 관리 API ==========

  /// 회원 목록 조회
  static Future<Map<String, dynamic>> getUsersList({
    int page = 1,
    int limit = 20,
  }) async {
    return await get('users/admin_list.php?page=$page&limit=$limit');
  }

  /// 회원 상태 변경
  static Future<Map<String, dynamic>> updateUserStatus(String userId, int status) async {
    return await post('users/update_status.php', {
      'user_id': userId,
      'status': status.toString(),
    });
  }

  // ========== 배너 관리 API ==========

  /// 배너 목록 조회
  static Future<Map<String, dynamic>> getBannersList() async {
    return await get('home/banners_admin.php');
  }

  /// 배너 추가
  static Future<Map<String, dynamic>> addBanner(Map<String, dynamic> bannerData) async {
    return await post('home/add_banner.php', bannerData);
  }

  /// 배너 삭제
  static Future<Map<String, dynamic>> deleteBanner(String bannerId) async {
    return await post('home/delete_banner.php', {'banner_id': bannerId});
  }

  // ========== 뉴스/공지 관리 API ==========

  /// 뉴스 목록 조회
  static Future<Map<String, dynamic>> getNewsList({
    int page = 1,
    int limit = 20,
  }) async {
    return await get('posts/admin_news_list.php?page=$page&limit=$limit');
  }

  /// 뉴스 추가
  static Future<Map<String, dynamic>> addNews(Map<String, dynamic> newsData) async {
    return await post('posts/add_news.php', newsData);
  }

  /// 뉴스 삭제
  static Future<Map<String, dynamic>> deleteNews(String newsId) async {
    return await post('posts/delete_news.php', {'news_id': newsId});
  }
}