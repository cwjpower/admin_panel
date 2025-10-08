// lib/models/user.dart

class User {
  final String uid;
  final String userName;
  final String displayName;
  final String userLevel;
  final String? profileImage;

  User({
    required this.uid,
    required this.userName,
    required this.displayName,
    required this.userLevel,
    this.profileImage,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      uid: json['uid'] ?? '',
      userName: json['user_name'] ?? '',
      displayName: json['display_name'] ?? '',
      userLevel: json['user_level'] ?? 'normal',
      profileImage: json['profile_image'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'uid': uid,
      'user_name': userName,
      'display_name': displayName,
      'user_level': userLevel,
      'profile_image': profileImage,
    };
  }
}