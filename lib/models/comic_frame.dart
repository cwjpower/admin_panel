// lib/models/comic_frame.dart

/// 만화 패널 (프레임) 데이터
class ComicFrame {
  final double x;      // 시작 X 좌표
  final double y;      // 시작 Y 좌표
  final double right;  // 끝 X 좌표
  final double bottom; // 끝 Y 좌표

  ComicFrame({
    required this.x,
    required this.y,
    required this.right,
    required this.bottom,
  });

  // 폭 계산
  double get width => right - x;

  // 높이 계산
  double get height => bottom - y;

  // JSON에서 생성
  factory ComicFrame.fromJson(Map<String, dynamic> json) {
    return ComicFrame(
      x: (json['x'] as num).toDouble(),
      y: (json['y'] as num).toDouble(),
      right: (json['right'] as num).toDouble(),
      bottom: (json['bottom'] as num).toDouble(),
    );
  }

  // JSON으로 변환
  Map<String, dynamic> toJson() {
    return {
      'x': x,
      'y': y,
      'right': right,
      'bottom': bottom,
    };
  }
}

/// 만화 페이지 (여러 패널 포함)
class ComicPage {
  final String name;              // 이미지 파일명
  final List<ComicFrame> frames;  // 패널 리스트

  ComicPage({
    required this.name,
    required this.frames,
  });

  // JSON에서 생성
  factory ComicPage.fromJson(Map<String, dynamic> json) {
    return ComicPage(
      name: json['name'] as String,
      frames: (json['frames'] as List)
          .map((f) => ComicFrame.fromJson(f as Map<String, dynamic>))
          .toList(),
    );
  }

  // JSON으로 변환
  Map<String, dynamic> toJson() {
    return {
      'name': name,
      'frames': frames.map((f) => f.toJson()).toList(),
    };
  }
}

/// 만화책 전체 (여러 페이지)
class ComicBook {
  final List<ComicPage> pages;

  ComicBook({required this.pages});

  factory ComicBook.fromJson(List<dynamic> json) {
    return ComicBook(
      pages: json.map((p) => ComicPage.fromJson(p as Map<String, dynamic>)).toList(),
    );
  }

  List<Map<String, dynamic>> toJson() {
    return pages.map((p) => p.toJson()).toList();
  }
}