import 'package:flutter/material.dart';

class AdminLoginScreen extends StatefulWidget {
  const AdminLoginScreen({Key? key}) : super(key: key);

  @override
  State<AdminLoginScreen> createState() => _AdminLoginScreenState();
}

class _AdminLoginScreenState extends State<AdminLoginScreen> {
  final _formKey = GlobalKey<FormState>();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  bool _isLoading = false;
  bool _obscurePassword = true;

  // 테스트 계정 목록
  final Map<String, Map<String, String>> _testAccounts = {
    'admin@herocomics.com': {
      'password': 'admin1234',
      'name': '슈퍼 관리자',
      'permission': 'super_admin',
    },
    'marvel@herocomics.com': {
      'password': 'marvel1234',
      'name': 'Marvel Comics',
      'permission': 'publisher_admin',
      'publisher_name': 'Marvel',
    },
    'dc@herocomics.com': {
      'password': 'dc1234',
      'name': 'DC Comics',
      'permission': 'publisher_admin',
      'publisher_name': 'DC',
    },
  };

  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  Future<void> _login() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _isLoading = true);

    // 시뮬레이션 딜레이
    await Future.delayed(const Duration(seconds: 1));

    final email = _emailController.text.trim();
    final password = _passwordController.text;

    // 테스트 계정 확인
    if (_testAccounts.containsKey(email)) {
      if (_testAccounts[email]!['password'] == password) {
        // 로그인 성공
        if (mounted) {
          Navigator.pushReplacementNamed(
            context,
            '/dashboard',
            arguments: _testAccounts[email],
          );
        }
      } else {
        _showError('비밀번호가 일치하지 않습니다.');
      }
    } else {
      _showError('등록되지 않은 이메일입니다.');
    }

    setState(() => _isLoading = false);
  }

  void _showError(String message) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(message),
        backgroundColor: Colors.red,
        behavior: SnackBarBehavior.floating,
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(
        decoration: BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
            colors: [Colors.red.shade700, Colors.red.shade900],
          ),
        ),
        child: Center(
          child: SingleChildScrollView(
            padding: const EdgeInsets.all(24),
            child: Card(
              elevation: 8,
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(16),
              ),
              child: Container(
                width: 450,
                padding: const EdgeInsets.all(40),
                child: Form(
                  key: _formKey,
                  child: Column(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      // 로고
                      Icon(
                        Icons.admin_panel_settings,
                        size: 80,
                        color: Colors.red.shade700,
                      ),
                      const SizedBox(height: 16),

                      // 타이틀
                      Text(
                        'HERO COMICS',
                        style: TextStyle(
                          fontSize: 28,
                          fontWeight: FontWeight.bold,
                          color: Colors.red.shade700,
                        ),
                      ),
                      const SizedBox(height: 8),
                      Text(
                        'Admin Panel',
                        style: TextStyle(
                          fontSize: 16,
                          color: Colors.grey.shade600,
                        ),
                      ),
                      const SizedBox(height: 40),

                      // 이메일 입력
                      TextFormField(
                        controller: _emailController,
                        decoration: InputDecoration(
                          labelText: '이메일',
                          prefixIcon: const Icon(Icons.email_outlined),
                          border: OutlineInputBorder(
                            borderRadius: BorderRadius.circular(12),
                          ),
                          filled: true,
                          fillColor: Colors.grey.shade50,
                        ),
                        keyboardType: TextInputType.emailAddress,
                        validator: (value) {
                          if (value == null || value.isEmpty) {
                            return '이메일을 입력해주세요';
                          }
                          if (!value.contains('@')) {
                            return '올바른 이메일 형식이 아닙니다';
                          }
                          return null;
                        },
                      ),
                      const SizedBox(height: 16),

                      // 비밀번호 입력
                      TextFormField(
                        controller: _passwordController,
                        decoration: InputDecoration(
                          labelText: '비밀번호',
                          prefixIcon: const Icon(Icons.lock_outlined),
                          suffixIcon: IconButton(
                            icon: Icon(
                              _obscurePassword
                                  ? Icons.visibility_outlined
                                  : Icons.visibility_off_outlined,
                            ),
                            onPressed: () {
                              setState(() {
                                _obscurePassword = !_obscurePassword;
                              });
                            },
                          ),
                          border: OutlineInputBorder(
                            borderRadius: BorderRadius.circular(12),
                          ),
                          filled: true,
                          fillColor: Colors.grey.shade50,
                        ),
                        obscureText: _obscurePassword,
                        validator: (value) {
                          if (value == null || value.isEmpty) {
                            return '비밀번호를 입력해주세요';
                          }
                          return null;
                        },
                      ),
                      const SizedBox(height: 24),

                      // 로그인 버튼
                      SizedBox(
                        width: double.infinity,
                        height: 50,
                        child: ElevatedButton(
                          onPressed: _isLoading ? null : _login,
                          style: ElevatedButton.styleFrom(
                            backgroundColor: Colors.red.shade700,
                            foregroundColor: Colors.white,
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(12),
                            ),
                          ),
                          child: _isLoading
                              ? const SizedBox(
                            height: 20,
                            width: 20,
                            child: CircularProgressIndicator(
                              strokeWidth: 2,
                              color: Colors.white,
                            ),
                          )
                              : const Text(
                            '로그인',
                            style: TextStyle(
                              fontSize: 16,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                        ),
                      ),
                      const SizedBox(height: 16),

                      // 테스트 계정 안내
                      Container(
                        padding: const EdgeInsets.all(12),
                        decoration: BoxDecoration(
                          color: Colors.blue.shade50,
                          borderRadius: BorderRadius.circular(8),
                          border: Border.all(color: Colors.blue.shade200),
                        ),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Row(
                              children: [
                                Icon(
                                  Icons.info_outline,
                                  size: 16,
                                  color: Colors.blue.shade700,
                                ),
                                const SizedBox(width: 8),
                                Text(
                                  '테스트 계정',
                                  style: TextStyle(
                                    fontWeight: FontWeight.bold,
                                    color: Colors.blue.shade700,
                                  ),
                                ),
                              ],
                            ),
                            const SizedBox(height: 8),
                            Text(
                              '슈퍼: admin@herocomics.com / admin1234',
                              style: TextStyle(
                                fontSize: 11,
                                color: Colors.blue.shade900,
                              ),
                            ),
                            Text(
                              'Marvel: marvel@herocomics.com / marvel1234',
                              style: TextStyle(
                                fontSize: 11,
                                color: Colors.blue.shade900,
                              ),
                            ),
                            Text(
                              'DC: dc@herocomics.com / dc1234',
                              style: TextStyle(
                                fontSize: 11,
                                color: Colors.blue.shade900,
                              ),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ),
          ),
        ),
      ),
    );
  }
}