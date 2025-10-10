class User {
  final String uid;
  final String userName;
  final String userEmail;
  final String displayName;
  final String userLevel;
  final String? profileImage;

  User({
    required this.uid,
    required this.userName,
    required this.userEmail,
    required this.displayName,
    required this.userLevel,
    this.profileImage,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      uid: json['uid'] ?? '',
      userName: json['user_name'] ?? '',
      userEmail: json['user_email'] ?? '',
      displayName: json['display_name'] ?? '',
      userLevel: json['user_level'] ?? '1',
      profileImage: json['profile_image'],
    );
  }
}