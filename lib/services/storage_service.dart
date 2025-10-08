import 'package:shared_preferences/shared_preferences.dart';

class StorageService {
  static const String _tokenKey = 'auth_token';
  static const String _uidKey = 'user_id';
  static const String _userNameKey = 'user_name';
  static const String _displayNameKey = 'display_name';
  static const String _userLevelKey = 'user_level';

  // 토큰 저장
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
    print('Auth data saved successfully');
  }

  // 토큰 불러오기
  static Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString(_tokenKey);
  }

  // 사용자 정보 불러오기
  static Future<Map<String, String?>> getUserData() async {
    final prefs = await SharedPreferences.getInstance();
    return {
      'token': prefs.getString(_tokenKey),
      'uid': prefs.getString(_uidKey),
      'user_name': prefs.getString(_userNameKey),
      'display_name': prefs.getString(_displayNameKey),
      'user_level': prefs.getString(_userLevelKey),
    };
  }

  // 로그인 여부 확인
  static Future<bool> isLoggedIn() async {
    final token = await getToken();
    return token != null && token.isNotEmpty;
  }

  // 로그아웃 (토큰 삭제)
  static Future<void> clearAuthData() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove(_tokenKey);
    await prefs.remove(_uidKey);
    await prefs.remove(_userNameKey);
    await prefs.remove(_displayNameKey);
    await prefs.remove(_userLevelKey);
    print('Auth data cleared');
  }
}