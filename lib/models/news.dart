// lib/models/news.dart

class News {
  final String id;
  final String title;
  final String summary;
  final String imageUrl;
  final String category;
  final DateTime date;
  final String? content;
  final String? author;
  final int? viewCount;

  News({
    required this.id,
    required this.title,
    required this.summary,
    required this.imageUrl,
    required this.category,
    required this.date,
    this.content,
    this.author,
    this.viewCount,
  });

  // JSON 변환 메서드 (API 연동 시 필요)
  factory News.fromJson(Map<String, dynamic> json) {
    return News(
      id: json['id'] ?? '',
      title: json['title'] ?? '',
      summary: json['summary'] ?? '',
      imageUrl: json['image_url'] ?? '',
      category: json['category'] ?? 'TOTAL',
      date: json['date'] != null
          ? DateTime.parse(json['date'])
          : DateTime.now(),
      content: json['content'],
      author: json['author'],
      viewCount: json['view_count'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'title': title,
      'summary': summary,
      'image_url': imageUrl,
      'category': category,
      'date': date.toIso8601String(),
      'content': content,
      'author': author,
      'view_count': viewCount,
    };
  }

  // 시간 경과 표시를 위한 헬퍼 메서드
  String getTimeAgo() {
    final difference = DateTime.now().difference(date);
    if (difference.inDays > 30) {
      return '${(difference.inDays / 30).floor()} months ago';
    } else if (difference.inDays > 0) {
      return '${difference.inDays} days ago';
    } else if (difference.inHours > 0) {
      return '${difference.inHours} hours ago';
    } else if (difference.inMinutes > 0) {
      return '${difference.inMinutes} minutes ago';
    } else {
      return 'Just now';
    }
  }

  // 카테고리별 색상 반환
  String getCategoryColor() {
    switch (category) {
      case 'MARVEL':
        return '#FF0000';  // Red
      case 'DC':
        return '#0000FF';  // Blue
      case 'IMAGE':
        return '#800080';  // Purple
      default:
        return '#000000';  // Black
    }
  }
}