import 'package:flutter/material.dart';

class AppColors {
  // 히어로코믹스 브랜드 컬러
  static const Color primary = Color(0xFFF1D548); // 노란색 #f1d548
  static const Color background = Color(0xFF131313); // 검은색 #131313
  static const Color cardBackground = Color(0xFF262626); // 회색 #262626

  // 브랜드별 색상
  static const Color marvelRed = Color(0xFFEA2328); // #ea2328
  static const Color dcBlue = Color(0xFF0376F2); // #0376f2
  static const Color imageGray = Color(0xFF626262); // #626262
  static const Color accent = marvelRed;

  // 텍스트 색상
  static const Color textPrimary = Color(0xFFFFFFFF); // 흰색
  static const Color textSecondary = Color(0xFF999999); // 회색
  static const Color textHint = Color(0xFFBDBDBD);

  // 기타 색상
  static const Color error = Color(0xFFD32F2F);
  static const Color success = Color(0xFF4CAF50);
  static const Color warning = Color(0xFFFFA726);

  // 그라데이션
  static const LinearGradient splashGradient = LinearGradient(
    colors: [Color(0xFFF1D548), Color(0xFFEA2328)],
    begin: Alignment.topCenter,
    end: Alignment.bottomCenter,
  );
}