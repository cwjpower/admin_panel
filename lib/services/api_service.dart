// lib/services/api_service.dart

import 'dart:convert';
import 'package:http/http.dart' as http;
import '../models/book.dart';
import '../models/news.dart';
import '../models/banner.dart';
import '../models/review.dart';
import '../models/user.dart';
import 'storage_service.dart';

class ApiService {
  // 서버 베이스 URL
  static const String baseUrl = 'http://34.64.84.117:8081/admin/apis';

  // 타임아웃 설정
  static const Duration timeout = Duration(seconds: 10);

  // ===========================================
  // 1. 사용자 인증 관련 API
  // ===========================================

  /// 로그인 API
  static Future<Map<String, dynamic>> login(String email, String password) async {
    try {
      print('Login attempt: $email');

      final response = await http.post(
        Uri.parse('$baseUrl/users/user_login_clean.php'),
        body: {
          'user_login': email,
          'user_pass': password,
        },
      ).timeout(timeout);

      print('Login response: ${response.body}');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        return data;
      } else {
        return {
          'code': 1,
          'msg': 'Server error: ${response.statusCode}',
        };
      }
    } catch (e) {
      print('Login error: $e');
      return {
        'code': 1,
        'msg': 'Connection failed: $e',
      };
    }
  }

  /// 사용자 프로필 가져오기
  static Future<User?> getUserProfile() async {
    try {
      final token = await StorageService.getToken();
      if (token == null) return null;

      final response = await http.get(
        Uri.parse('$baseUrl/users/profile.php'),
        headers: {
          'Authorization': 'Bearer $token',
        },
      ).timeout(timeout);

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (data['code'] == 0) {
          return User.fromJson(data['data']);
        }
      }
      return null;
    } catch (e) {
      print('Get profile error: $e');
      return null;
    }
  }

  // ===========================================
  // 2. 책 관련 API
  // ===========================================

  /// 책 목록 가져오기
  static Future<List<Book>> getBooks({
    String? category,  // MARVEL, DC, IMAGE
    String? search,    // 검색어
    String? sort,      // newest, oldest, price_low, price_high
    int limit = 20,
    int offset = 0,
  }) async {
    try {
      // URL 파라미터 구성
      final params = <String, String>{
        'limit': limit.toString(),
        'offset': offset.toString(),
      };

      if (category != null && category.isNotEmpty) {
        params['category'] = category;
      }
      if (search != null && search.isNotEmpty) {
        params['search'] = search;
      }
      if (sort != null && sort.isNotEmpty) {
        params['sort'] = sort;
      }

      // URL 생성
      final uri = Uri.parse('$baseUrl/books/list.php').replace(queryParameters: params);
      print('Fetching books from: $uri');

      // HTTP 요청
      final response = await http.get(uri).timeout(timeout);

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        print('Books API response code: ${data['code']}');

        if (data['code'] == 0) {
          List<Book> books = [];
          for (var item in data['data']) {
            books.add(Book.fromJson(item));
          }
          print('Loaded ${books.length} books');
          return books;
        } else {
          print('API Error: ${data['msg']}');
          return [];
        }
      } else {
        print('HTTP Error: ${response.statusCode}');
        return [];
      }
    } catch (e) {
      print('Exception fetching books: $e');
      return [];
    }
  }

  /// 책 상세 정보 가져오기
  static Future<Book?> getBookDetail(String bookId) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/books/detail.php?id=$bookId'),
      ).timeout(timeout);

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (data['code'] == 0) {
          return Book.fromJson(data['data']);
        }
      }
      return null;
    } catch (e) {
      print('Get book detail error: $e');
      return null;
    }
  }

  /// 책 리뷰 가져오기
  static Future<List<Review>> getBookReviews(String bookId) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/books/reviews.php?book_id=$bookId'),
      ).timeout(timeout);

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (data['code'] == 0) {
          List<Review> reviews = [];
          for (var item in data['data']) {
            reviews.add(Review.fromJson(item));
          }
          return reviews;
        }
      }
      return [];
    } catch (e) {
      print('Get reviews error: $e');
      return [];
    }
  }

  // ===========================================
  // 3. 뉴스/공지사항 관련 API
  // ===========================================

  /// 뉴스 목록 가져오기
  static Future<List<News>> getNews({
    String category = 'TOTAL',  // TOTAL, MARVEL, DC, IMAGE
    int limit = 10,
  }) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/posts/news_list.php?category=$category&limit=$limit'),
      ).timeout(timeout);

      print('News API response status: ${response.statusCode}');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (data['code'] == 0) {
          List<News> news = [];
          for (var item in data['data']) {
            news.add(News.fromJson(item));
          }
          print('Loaded ${news.length} news items');
          return news;
        }
      }
      return [];
    } catch (e) {
      print('Error fetching news: $e');
      return [];
    }
  }

  /// 뉴스 상세 가져오기
  static Future<News?> getNewsDetail(String newsId) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/posts/news_detail.php?id=$newsId'),
      ).timeout(timeout);

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (data['code'] == 0) {
          return News.fromJson(data['data']);
        }
      }
      return null;
    } catch (e) {
      print('Get news detail error: $e');
      return null;
    }
  }

  // ===========================================
  // 4. 홈 화면 관련 API
  // ===========================================

  /// 배너 목록 가져오기
  static Future<List<AppBanner>> getBanners() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/home/banners.php'),
      ).timeout(timeout);

      print('Banners API response status: ${response.statusCode}');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (data['code'] == 0) {
          List<AppBanner> banners = [];
          for (var item in data['data']) {
            banners.add(AppBanner.fromJson(item));
          }
          print('Loaded ${banners.length} banners');
          return banners;
        }
      }
      return [];
    } catch (e) {
      print('Error fetching banners: $e');
      return [];
    }
  }

  /// 홈 화면 데이터 한번에 가져오기 (최적화)
  static Future<Map<String, dynamic>> getHomeData() async {
    try {
      // 병렬로 여러 API 호출
      final results = await Future.wait([
        getBanners(),
        getNews(category: 'TOTAL', limit: 5),
        getBooks(sort: 'newest', limit: 10),
      ]);

      return {
        'banners': results[0],
        'news': results[1],
        'books': results[2],
      };
    } catch (e) {
      print('Error fetching home data: $e');
      return {
        'banners': [],
        'news': [],
        'books': [],
      };
    }
  }

  // ===========================================
  // 5. 사용자 라이브러리 관련 API
  // ===========================================

  /// 구매한 책 목록
  static Future<List<Book>> getPurchasedBooks() async {
    try {
      final token = await StorageService.getToken();
      if (token == null) return [];

      final response = await http.get(
        Uri.parse('$baseUrl/users/purchased_books.php'),
        headers: {
          'Authorization': 'Bearer $token',
        },
      ).timeout(timeout);

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (data['code'] == 0) {
          List<Book> books = [];
          for (var item in data['data']) {
            books.add(Book.fromJson(item));
          }
          return books;
        }
      }
      return [];
    } catch (e) {
      print('Get purchased books error: $e');
      return [];
    }
  }

  /// 위시리스트
  static Future<List<Book>> getWishlist() async {
    try {
      final token = await StorageService.getToken();
      if (token == null) return [];

      final response = await http.get(
        Uri.parse('$baseUrl/users/wishlist.php'),
        headers: {
          'Authorization': 'Bearer $token',
        },
      ).timeout(timeout);

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (data['code'] == 0) {
          List<Book> books = [];
          for (var item in data['data']) {
            books.add(Book.fromJson(item));
          }
          return books;
        }
      }
      return [];
    } catch (e) {
      print('Get wishlist error: $e');
      return [];
    }
  }

  // ===========================================
  // 6. 액션 관련 API
  // ===========================================

  /// 위시리스트에 추가/제거
  static Future<bool> toggleWishlist(String bookId) async {
    try {
      final token = await StorageService.getToken();
      if (token == null) return false;

      final response = await http.post(
        Uri.parse('$baseUrl/users/toggle_wishlist.php'),
        headers: {
          'Authorization': 'Bearer $token',
        },
        body: {
          'book_id': bookId,
        },
      ).timeout(timeout);

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        return data['code'] == 0;
      }
      return false;
    } catch (e) {
      print('Toggle wishlist error: $e');
      return false;
    }
  }

  /// 장바구니에 추가
  static Future<bool> addToCart(String bookId) async {
    try {
      final token = await StorageService.getToken();
      if (token == null) return false;

      final response = await http.post(
        Uri.parse('$baseUrl/users/add_to_cart.php'),
        headers: {
          'Authorization': 'Bearer $token',
        },
        body: {
          'book_id': bookId,
        },
      ).timeout(timeout);

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        return data['code'] == 0;
      }
      return false;
    } catch (e) {
      print('Add to cart error: $e');
      return false;
    }
  }

  /// 리뷰 작성
  static Future<bool> submitReview({
    required String bookId,
    required int rating,
    required String comment,
  }) async {
    try {
      final token = await StorageService.getToken();
      if (token == null) return false;

      final response = await http.post(
        Uri.parse('$baseUrl/books/submit_review.php'),
        headers: {
          'Authorization': 'Bearer $token',
        },
        body: {
          'book_id': bookId,
          'rating': rating.toString(),
          'comment': comment,
        },
      ).timeout(timeout);

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        return data['code'] == 0;
      }
      return false;
    } catch (e) {
      print('Submit review error: $e');
      return false;
    }
  }

  // ===========================================
  // 7. 검색 관련 API
  // ===========================================

  /// 통합 검색
  static Future<Map<String, dynamic>> search(String query) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/search/all.php?q=${Uri.encodeComponent(query)}'),
      ).timeout(timeout);

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (data['code'] == 0) {
          return {
            'books': (data['data']['books'] as List)
                .map((item) => Book.fromJson(item))
                .toList(),
            'news': (data['data']['news'] as List)
                .map((item) => News.fromJson(item))
                .toList(),
          };
        }
      }
      return {
        'books': [],
        'news': [],
      };
    } catch (e) {
      print('Search error: $e');
      return {
        'books': [],
        'news': [],
      };
    }
  }

  // ===========================================
  // 8. 유틸리티 메서드
  // ===========================================

  /// API 연결 테스트
  static Future<bool> testConnection() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/test.php'),
      ).timeout(Duration(seconds: 5));

      return response.statusCode == 200;
    } catch (e) {
      print('Connection test failed: $e');
      return false;
    }
  }

  /// 이미지 URL 검증 및 기본 이미지 반환
  static String getValidImageUrl(String? url) {
    if (url == null || url.isEmpty) {
      return 'https://via.placeholder.com/300x450?text=No+Image';
    }

    // 상대 경로인 경우 절대 경로로 변환
    if (!url.startsWith('http')) {
      return '$baseUrl/../$url';
    }

    return url;
  }
}