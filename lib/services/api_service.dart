import 'dart:convert';
import 'package:http/http.dart' as http;

class ApiService {
  // 서버 URL
  static const String baseUrl = 'http://34.64.84.117:8081/admin/apis';

  // 로그인
  static Future<Map<String, dynamic>> login(String email, String password) async {
    print('Login attempt - Email: $email');

    try {
      final url = '$baseUrl/users/user_login_clean.php';
      print('API URL: $url');

      final response = await http.post(
        Uri.parse(url),
        body: {
          'user_login': email,
          'user_pass': password,
        },
      );

      print('Response status: ${response.statusCode}');
      print('Response body: ${response.body}');

      if (response.statusCode == 200) {
        print('Raw response: ${response.body}');

        final data = json.decode(response.body);

        if (data['code'] == 0) {
          print('Login successful!');
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
          print('Login failed with code: ${data['code']}');
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
      print('Login error: $e');
      return {
        'success': false,
        'message': '네트워크 오류가 발생했습니다.',
      };
    }
  }

  // 책 목록 조회
  static Future<List<dynamic>> getBooks({String? category}) async {
    try {
      String url = '$baseUrl/books/list.php';
      if (category != null && category.isNotEmpty) {
        url += '?category=$category';
      }

      print('Fetching books from: $url');

      final response = await http.get(Uri.parse(url));
      print('Books response status: ${response.statusCode}');
      print('Books response body: ${response.body}');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (data['code'] == 0) {
          return data['books'] ?? [];
        }
      }
      return [];
    } catch (e) {
      print('Error fetching books: $e');
      return [];
    }
  }

  // 뉴스/공지사항 목록 조회
  static Future<List<dynamic>> getPosts({String? category}) async {
    try {
      String url = '$baseUrl/posts/list.php';
      if (category != null && category.isNotEmpty) {
        url += '?category=$category';
      }

      print('Fetching posts from: $url');

      final response = await http.get(Uri.parse(url));
      print('Posts response status: ${response.statusCode}');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (data['code'] == 0) {
          return data['posts'] ?? [];
        }
      }
      return [];
    } catch (e) {
      print('Error fetching posts: $e');
      return [];
    }
  }

  // 배너 목록 조회
  static Future<List<dynamic>> getBanners() async {
    try {
      String url = '$baseUrl/banners/list.php';

      print('Fetching banners from: $url');

      final response = await http.get(Uri.parse(url));
      print('Banners response status: ${response.statusCode}');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (data['code'] == 0) {
          return data['banners'] ?? [];
        }
      }
      return [];
    } catch (e) {
      print('Error fetching banners: $e');
      return [];
    }
  }
}