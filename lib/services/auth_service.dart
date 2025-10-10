import 'dart:convert';
import 'package:http/http.dart' as http;

class AuthService {
  static const String baseUrl = 'https://herocomics.co.kr/admin/apis';
  static const Duration _timeout = Duration(seconds: 15);

  static Future<Map<String, dynamic>> userlogin(String email, String password) async {
    final url = Uri.parse('$baseUrl/users/user_login.php');
    try {
      final res = await http
          .post(
        url,
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: {'email': email, 'password': password}, // 서버 규격
      )
          .timeout(_timeout);

      if (res.statusCode != 200 && res.statusCode != 204) {
        return {'success': false, 'message': 'Server error: ${res.statusCode}'};
      }

      final text = res.body.trim();
      final Map<String, dynamic> data =
      text.isEmpty ? {} : jsonDecode(text) as Map<String, dynamic>;

      if ((data['code'] ?? -1) != 0) {
        final code = data['code'] is int ? data['code'] as int : -1;
        return {
          'success': false,
          'code': code,
          'message': (data['msg'] ?? _getErrorMessage(code)).toString(),
        };
      }

      // 성공 필드: code, uid, email, user_name
      return {
        'success': true,
        'uid': data['uid'],
        'email': data['email'],
        'user_name': data['user_name'],
      };
    } catch (e) {
      return {'success': false, 'message': 'Network error: $e'};
    }
  }

  static String _getErrorMessage(int code) {
    switch (code) {
      case 510:
        return '계정(이메일 주소)을 입력해 주십시오.';
      case 602:
        return '비밀번호를 입력해 주십시오.';
      case 501:
        return '존재하지 않는 이메일입니다.';
      case 601:
        return '비밀번호가 올바르지 않습니다.';
      case 503:
        return '차단된 회원입니다.';
      case 504:
        return '탈퇴한 회원입니다.';
      default:
        return '로그인에 실패했습니다. (코드: $code)';
    }
  }
}
