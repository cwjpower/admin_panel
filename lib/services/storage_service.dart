import 'package:shared_preferences/shared_preferences.dart';
import 'dart:convert';  // json 인코딩/디코딩용

class StorageService {
  static const String _tokenKey = 'auth_token';
  static const String _uidKey = 'uid';
  static const String _userLoginKey = 'user_login';
  static const String _userNameKey = 'user_name';
  static const String _displayNameKey = 'display_name';
  static const String _userLevelKey = 'user_level';

  // 인증 데이터 저장
  static Future<void> saveAuthData({
    required String token,
    required String uid,
    required String userName,
    required String displayName,
    required String userLevel,
  }) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(_tokenKey, token);
    await prefs.setString(_uidKey, uid);
    await prefs.setString(_userNameKey, userName);
    await prefs.setString(_displayNameKey, displayName);
    await prefs.setString(_userLevelKey, userLevel);
  }

  // 토큰 가져오기
  static Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString(_tokenKey);
  }

  // UID 가져오기
  static Future<String?> getUid() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString(_uidKey);
  }

  // 사용자 이름 가져오기
  static Future<String?> getUserName() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString(_userNameKey);
  }

  // 사용자 이메일 가져오기 (user_login이 이메일)
  static Future<String?> getUserEmail() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString(_userLoginKey);
  }

  // 표시 이름 가져오기
  static Future<String?> getDisplayName() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString(_displayNameKey);
  }

  // 사용자 레벨 가져오기
  static Future<String?> getUserLevel() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString(_userLevelKey);
  }

  // 로그인 여부 확인
  static Future<bool> isLoggedIn() async {
    final token = await getToken();
    return token != null && token.isNotEmpty;
  }

  // 모든 인증 데이터 삭제 (로그아웃)
  static Future<void> clearAuthData() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove(_tokenKey);
    await prefs.remove(_uidKey);
    await prefs.remove(_userLoginKey);
    await prefs.remove(_userNameKey);
    await prefs.remove(_displayNameKey);
    await prefs.remove(_userLevelKey);
  }

  // 로그인 시 이메일도 저장하도록 수정
  static Future<void> saveLoginData({
    required String token,
    required String uid,
    required String userLogin,  // 이메일
    required String userName,
    required String displayName,
    required String userLevel,
  }) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(_tokenKey, token);
    await prefs.setString(_uidKey, uid);
    await prefs.setString(_userLoginKey, userLogin);  // 이메일 저장
    await prefs.setString(_userNameKey, userName);
    await prefs.setString(_displayNameKey, displayName);
    await prefs.setString(_userLevelKey, userLevel);


  }

  // 읽기 진도 저장
  static Future<void> saveReadingProgress(
      String bookId,
      int episode,
      int pageNum,
      ) async {
    final prefs = await SharedPreferences.getInstance();
    final key = 'reading_progress_${bookId}_$episode';
    await prefs.setInt(key, pageNum);

    // 마지막 읽은 시간도 저장
    final timeKey = 'reading_time_${bookId}_$episode';
    await prefs.setString(timeKey, DateTime.now().toIso8601String());
  }

  // 읽기 진도 불러오기
  static Future<int?> getReadingProgress(
      String bookId,
      int episode,
      ) async {
    final prefs = await SharedPreferences.getInstance();
    final key = 'reading_progress_${bookId}_$episode';
    return prefs.getInt(key);
  }

  // 마지막 읽은 시간 가져오기
  static Future<String?> getLastReadTime(
      String bookId,
      int episode,
      ) async {
    final prefs = await SharedPreferences.getInstance();
    final timeKey = 'reading_time_${bookId}_$episode';
    return prefs.getString(timeKey);
  }

  // 모든 진도 삭제
  static Future<void> clearReadingProgress(String bookId) async {
    final prefs = await SharedPreferences.getInstance();
    final keys = prefs.getKeys();
    for (String key in keys) {
      if (key.contains('reading_progress_$bookId') ||
          key.contains('reading_time_$bookId')) {
        await prefs.remove(key);
      }
    }
  }

  // 북마크 저장
  static Future<void> saveBookmark(
      String bookId,
      int episode,
      int pageNum,
      String? memo,
      ) async {
    final prefs = await SharedPreferences.getInstance();
    final key = 'bookmark_${bookId}_${episode}_$pageNum';
    final data = {
      'bookId': bookId,
      'episode': episode,
      'pageNum': pageNum,
      'memo': memo ?? '',
      'timestamp': DateTime.now().toIso8601String(),
    };
    await prefs.setString(key, json.encode(data));
  }

  // 북마크 목록 가져오기
  static Future<List<Map<String, dynamic>>> getBookmarks(String bookId) async {
    final prefs = await SharedPreferences.getInstance();
    final keys = prefs.getKeys();
    final bookmarks = <Map<String, dynamic>>[];

    for (String key in keys) {
      if (key.startsWith('bookmark_$bookId')) {
        final data = prefs.getString(key);
        if (data != null) {
          bookmarks.add(json.decode(data));
        }
      }
    }

    // 시간순 정렬
    bookmarks.sort((a, b) =>
        DateTime.parse(b['timestamp']).compareTo(DateTime.parse(a['timestamp']))
    );

    return bookmarks;
  }
}
