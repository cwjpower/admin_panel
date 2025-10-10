import 'dart:convert';
import 'package:http/http.dart' as http;
import '../models/book.dart';
import '../models/banner.dart' as banner_model;
import '../models/news.dart';
import '../models/user.dart';

class ApiService {
  static const String baseUrl = 'http://34.64.84.117:8081/admin/apis';

  // 로그인
  static Future<Map<String, dynamic>> login(String email, String password) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/users/user_login_clean.php'),
        body: {
          'user_login': email,
          'user_pass': password,
        },
      );

      print('Login response: ${response.body}');

      if (response.statusCode == 200) {
        return json.decode(response.body);
      }
      return {'code': 1, 'msg': '서버 연결 실패'};
    } catch (e) {
      print('Login error: $e');
      return {'code': 1, 'msg': e.toString()};
    }
  }

  // 책 목록 가져오기
  static Future<List<Book>> fetchBooks({String? category}) async {
    try {
      String url = '$baseUrl/books/list.php';
      if (category != null && category.isNotEmpty) {
        url += '?category=$category';
      }

      print('Fetching books from: $url');
      final response = await http.get(Uri.parse(url));
      print('Books response: ${response.body}');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (data['success'] == true) {
          return (data['books'] as List)
              .map((book) => Book.fromJson(book))
              .toList();
        }
      }
      return [];
    } catch (e) {
      print('Error fetching books: $e');
      return [];
    }
  }

  // 배너 가져오기
  static Future<List<banner_model.Banner>> fetchBanners() async {
    try {
      final response = await http.get(
          Uri.parse('$baseUrl/home/banners.php')
      );
      print('Banners response: ${response.body}');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (data['success'] == true) {
          return (data['banners'] as List)
              .map((banner) => banner_model.Banner.fromJson(banner))
              .toList();
        }
      }
      return [];
    } catch (e) {
      print('Error fetching banners: $e');
      return [];
    }
  }

  // 뉴스 가져오기 (Mock 데이터 - 나중에 API 연동)
  static Future<List<News>> fetchNews({String? category}) async {
    await Future.delayed(Duration(seconds: 1));

    // Mock 데이터
    List<News> mockNews = [
      News(
        id: 1,
        title: 'Marvel Announces New Spider-Man Series',
        content: 'A brand new Spider-Man series is coming...',
        category: 'MARVEL',
        imageUrl: 'https://via.placeholder.com/300x150',
        date: DateTime.now().subtract(Duration(days: 1)),
      ),
      News(
        id: 2,
        title: 'DC Comics Batman Special Edition',
        content: 'Batman returns in this special edition...',
        category: 'DC',
        imageUrl: 'https://via.placeholder.com/300x150',
        date: DateTime.now().subtract(Duration(days: 2)),
      ),
      News(
        id: 3,
        title: 'X-Men Phoenix Saga Remastered',
        content: 'The classic Phoenix Saga gets remastered...',
        category: 'MARVEL',
        imageUrl: 'https://via.placeholder.com/300x150',
        date: DateTime.now().subtract(Duration(days: 3)),
      ),
    ];

    if (category != null && category != 'TOTAL') {
      return mockNews.where((news) => news.category == category).toList();
    }

    return mockNews;
  }

  // 책 검색
  static Future<List<Book>> searchBooks(String query) async {
    try {
      final response = await http.get(
          Uri.parse('$baseUrl/books/list.php?search=${Uri.encodeComponent(query)}')
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (data['success'] == true) {
          return (data['books'] as List)
              .map((book) => Book.fromJson(book))
              .toList();
        }
      }
      return [];
    } catch (e) {
      print('Error searching books: $e');
      return [];
    }
  }

  // 사용자 프로필 (Mock)
  static Future<User?> fetchUserProfile(String userId) async {
    await Future.delayed(Duration(seconds: 1));

    return User(
      uid: userId,
      userName: '테스터',
      userEmail: 'test@test.com',
      displayName: '테스터',
      userLevel: '1',
      profileImage: 'https://via.placeholder.com/150',
    );
  }
}