import 'package:flutter/material.dart';
import 'screens/splash_screen.dart';  // 추가!
import 'screens/login_screen.dart';
import 'screens/home_screen.dart';

void main() {
  print("App starting...");
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Hero Comics',
      theme: ThemeData(
        primarySwatch: Colors.blue,
        colorScheme: ColorScheme.fromSeed(seedColor: Colors.amber),
      ),
      home: const SplashScreen(),  // ← 여기 변경!
      debugShowCheckedModeBanner: false,
      routes: {
        '/home': (context) => const HomeScreen(),
        '/login': (context) => const LoginScreen(),
      },
    );
  }
}