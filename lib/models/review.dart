// lib/models/review.dart

class Review {
  final String id;
  final String userName;
  final String? userProfileImage;
  final int rating;
  final String comment;
  final DateTime date;
  final String bookId;
  final String? bookTitle;
  final int? helpfulCount;
  final int? unhelpfulCount;
  final bool? isVerifiedPurchase;
  final bool? isSpoiler;
  final List<String>? images;
  final String? userLevel;
  final bool? isEdited;
  final DateTime? editedDate;
  final String? adminReply;
  final DateTime? adminReplyDate;

  Review({
    required this.id,
    required this.userName,
    this.userProfileImage,
    required this.rating,
    required this.comment,
    required this.date,
    required this.bookId,
    this.bookTitle,
    this.helpfulCount,
    this.unhelpfulCount,
    this.isVerifiedPurchase,
    this.isSpoiler,
    this.images,
    this.userLevel,
    this.isEdited,
    this.editedDate,
    this.adminReply,
    this.adminReplyDate,
  });

  // JSON 변환 메서드 (API 연동 시 필요)
  factory Review.fromJson(Map<String, dynamic> json) {
    return Review(
      id: json['id'] ?? '',
      userName: json['user_name'] ?? 'Anonymous',
      userProfileImage: json['user_profile_image'],
      rating: json['rating'] ?? 0,
      comment: json['comment'] ?? '',
      date: json['date'] != null
          ? DateTime.parse(json['date'])
          : DateTime.now(),
      bookId: json['book_id'] ?? '',
      bookTitle: json['book_title'],
      helpfulCount: json['helpful_count'] ?? 0,
      unhelpfulCount: json['unhelpful_count'] ?? 0,
      isVerifiedPurchase: json['is_verified_purchase'] ?? false,
      isSpoiler: json['is_spoiler'] ?? false,
      images: json['images'] != null
          ? List<String>.from(json['images'])
          : null,
      userLevel: json['user_level'],
      isEdited: json['is_edited'] ?? false,
      editedDate: json['edited_date'] != null
          ? DateTime.parse(json['edited_date'])
          : null,
      adminReply: json['admin_reply'],
      adminReplyDate: json['admin_reply_date'] != null
          ? DateTime.parse(json['admin_reply_date'])
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'user_name': userName,
      'user_profile_image': userProfileImage,
      'rating': rating,
      'comment': comment,
      'date': date.toIso8601String(),
      'book_id': bookId,
      'book_title': bookTitle,
      'helpful_count': helpfulCount,
      'unhelpful_count': unhelpfulCount,
      'is_verified_purchase': isVerifiedPurchase,
      'is_spoiler': isSpoiler,
      'images': images,
      'user_level': userLevel,
      'is_edited': isEdited,
      'edited_date': editedDate?.toIso8601String(),
      'admin_reply': adminReply,
      'admin_reply_date': adminReplyDate?.toIso8601String(),
    };
  }

  // 시간 경과 표시
  String getTimeAgo() {
    final difference = DateTime.now().difference(date);
    if (difference.inDays > 365) {
      return '${(difference.inDays / 365).floor()} years ago';
    } else if (difference.inDays > 30) {
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

  // 편집 시간 표시
  String? getEditedTimeAgo() {
    if (editedDate == null) return null;
    final difference = DateTime.now().difference(editedDate!);
    if (difference.inDays > 0) {
      return 'Edited ${difference.inDays} days ago';
    } else if (difference.inHours > 0) {
      return 'Edited ${difference.inHours} hours ago';
    } else {
      return 'Edited recently';
    }
  }

  // 유용성 점수 계산
  int getHelpfulScore() {
    return (helpfulCount ?? 0) - (unhelpfulCount ?? 0);
  }

  // 유용성 퍼센트 계산
  String getHelpfulPercentage() {
    final total = (helpfulCount ?? 0) + (unhelpfulCount ?? 0);
    if (total == 0) return '0%';
    final percentage = ((helpfulCount ?? 0) / total * 100).round();
    return '$percentage%';
  }

  // 별점 표시용 리스트
  List<bool> getStarList() {
    return List.generate(5, (index) => index < rating);
  }

  // 사용자 이니셜 (프로필 이미지 없을 때)
  String getUserInitials() {
    if (userName.isEmpty) return '?';
    final words = userName.split(' ');
    if (words.length >= 2) {
      return '${words[0][0]}${words[1][0]}'.toUpperCase();
    }
    return userName[0].toUpperCase();
  }

  // 리뷰 라벨 리스트
  List<String> getLabels() {
    List<String> labels = [];
    if (isVerifiedPurchase == true) labels.add('Verified Purchase');
    if (isSpoiler == true) labels.add('Spoiler');
    if (isEdited == true) labels.add('Edited');
    if (adminReply != null) labels.add('Admin Replied');
    return labels;
  }

  // 평점 텍스트
  String getRatingText() {
    switch (rating) {
      case 5:
        return 'Excellent';
      case 4:
        return 'Good';
      case 3:
        return 'Average';
      case 2:
        return 'Poor';
      case 1:
        return 'Terrible';
      default:
        return 'No rating';
    }
  }
}