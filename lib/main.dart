import 'package:flutter/material.dart';
import 'screens/splash_screen.dart';
import 'screens/login_screen.dart';
import 'screens/home_screen.dart';
import 'screens/my_page_screen.dart';
// import 'screens/admin_login_screen.dart';  // 주석처리
// import 'screens/admin_layout.dart';  // 주석처리

void main() {
  runApp(const HeroComicsApp());
}

class HeroComicsApp extends StatelessWidget {
  const HeroComicsApp({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'HeroComics',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        primarySwatch: Colors.red,
        useMaterial3: true,
      ),
      home: const SplashScreen(),
      routes: {
        '/home': (context) => const HomeScreen(),
        '/login': (context) => const LoginScreen(),
        '/mypage': (context) => const MyPageScreen(),
      },
    );
  }
}