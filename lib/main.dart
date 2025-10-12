// admin_app/lib/main.dart
import 'package:flutter/material.dart';
import 'screens/books_manage_screen.dart';  // AllBooksManageScreen이 여기 있음
import 'screens/publishers_manage_screen.dart';

void main() {
  runApp(const HeroComicsAdminApp());
}

class HeroComicsAdminApp extends StatelessWidget {
  const HeroComicsAdminApp({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'HeroComics Admin',
      theme: ThemeData(
        primarySwatch: Colors.blue,
        useMaterial3: true,
      ),
      debugShowCheckedModeBanner: false,
      home: const AdminDashboard(),
      routes: {
        '/books_manage': (context) => const AllBooksManageScreen(),  // AllBooks!
        '/publishers_manage': (context) => const PublishersManageScreen(),
        // VolumeManageScreen은 파라미터 필요해서 직접 push로 호출
      },
    );
  }
}

class AdminDashboard extends StatefulWidget {
  const AdminDashboard({Key? key}) : super(key: key);

  @override
  State<AdminDashboard> createState() => _AdminDashboardState();
}

class _AdminDashboardState extends State<AdminDashboard> {
  String selectedMenu = 'dashboard';

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Row(
        children: [
          // 사이드바
          Container(
            width: 250,
            color: const Color(0xFF1E1E1E),
            child: Column(
              children: [
                // 로고
                Container(
                  padding: const EdgeInsets.all(20),
                  child: Column(
                    children: [
                      Container(
                        width: 60,
                        height: 60,
                        decoration: BoxDecoration(
                          color: Colors.red,
                          borderRadius: BorderRadius.circular(12),
                        ),
                        child: const Icon(
                          Icons.menu_book,
                          color: Colors.white,
                          size: 32,
                        ),
                      ),
                      const SizedBox(height: 12),
                      const Text(
                        'HERO COMICS',
                        style: TextStyle(
                          color: Colors.white,
                          fontSize: 18,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      const Text(
                        'Super Admin',
                        style: TextStyle(
                          color: Colors.white70,
                          fontSize: 12,
                        ),
                      ),
                    ],
                  ),
                ),
                const Divider(color: Colors.white24),

                // 메뉴
                Expanded(
                  child: ListView(
                    padding: EdgeInsets.zero,
                    children: [
                      // 대시보드
                      _buildMenuSection('메인'),
                      _buildMenuItem(
                        icon: Icons.dashboard,
                        title: '대시보드',
                        id: 'dashboard',
                        onTap: () {
                          setState(() => selectedMenu = 'dashboard');
                        },
                      ),

                      // 콘텐츠 관리
                      _buildMenuSection('콘텐츠 관리'),
                      _buildMenuItem(
                        icon: Icons.library_books,
                        title: '출판사 관리',
                        id: 'publisher',
                        badge: 'SUPER',
                        badgeColor: Colors.purple,
                        onTap: () {
                          Navigator.pushNamed(context, '/publishers_manage');  // 복수형!
                        },
                      ),
                      _buildMenuItem(
                        icon: Icons.collections_bookmark,
                        title: '전체 책 관리',
                        id: 'all_books',
                        onTap: () {
                          Navigator.pushNamed(context, '/books_manage');
                        },
                      ),
                      _buildMenuItem(
                        icon: Icons.book,
                        title: '시리즈 관리',
                        id: 'series',
                        onTap: () {
                          Navigator.pushNamed(context, '/books_manage');
                        },
                      ),

                      // 사용자 관리
                      _buildMenuSection('사용자 관리'),
                      _buildMenuItem(
                        icon: Icons.people,
                        title: '회원 관리',
                        id: 'users',
                        onTap: () {
                          setState(() => selectedMenu = 'users');
                        },
                      ),
                      _buildMenuItem(
                        icon: Icons.shopping_cart,
                        title: '주문 관리',
                        id: 'orders',
                        onTap: () {
                          setState(() => selectedMenu = 'orders');
                        },
                      ),
                      _buildMenuItem(
                        icon: Icons.loyalty,
                        title: '커뮤니티 관리',
                        id: 'community',
                        onTap: () {
                          setState(() => selectedMenu = 'community');
                        },
                      ),

                      // 사이트 설정
                      _buildMenuSection('사이트 설정'),
                      _buildMenuItem(
                        icon: Icons.image,
                        title: '배너 관리',
                        id: 'banner',
                        onTap: () {
                          setState(() => selectedMenu = 'banner');
                        },
                      ),
                      _buildMenuItem(
                        icon: Icons.article,
                        title: '뉴스/공지',
                        id: 'news',
                        onTap: () {
                          setState(() => selectedMenu = 'news');
                        },
                      ),
                      _buildMenuItem(
                        icon: Icons.confirmation_number,
                        title: '캐시/쿠폰',
                        id: 'coupon',
                        onTap: () {
                          setState(() => selectedMenu = 'coupon');
                        },
                      ),

                      // 기타
                      _buildMenuSection('기타'),
                      _buildMenuItem(
                        icon: Icons.bar_chart,
                        title: '전체 통계',
                        id: 'stats',
                        onTap: () {
                          setState(() => selectedMenu = 'stats');
                        },
                      ),
                      _buildMenuItem(
                        icon: Icons.settings,
                        title: '시스템 설정',
                        id: 'settings',
                        onTap: () {
                          setState(() => selectedMenu = 'settings');
                        },
                      ),
                    ],
                  ),
                ),

                // 하단 프로필
                Container(
                  padding: const EdgeInsets.all(16),
                  decoration: const BoxDecoration(
                    border: Border(
                      top: BorderSide(color: Colors.white24),
                    ),
                  ),
                  child: Row(
                    children: [
                      const CircleAvatar(
                        backgroundColor: Colors.blue,
                        child: Icon(Icons.person, color: Colors.white),
                      ),
                      const SizedBox(width: 12),
                      const Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              '슈퍼 관리자',
                              style: TextStyle(
                                color: Colors.white,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                            Text(
                              '슈퍼 권한',
                              style: TextStyle(
                                color: Colors.white70,
                                fontSize: 12,
                              ),
                            ),
                          ],
                        ),
                      ),
                      IconButton(
                        icon: const Icon(Icons.logout, color: Colors.white70),
                        onPressed: () {
                          // 로그아웃 처리
                        },
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),

          // 메인 컨텐츠
          Expanded(
            child: Container(
              color: Colors.grey[100],
              child: Column(
                children: [
                  // 헤더
                  Container(
                    padding: const EdgeInsets.all(20),
                    decoration: const BoxDecoration(
                      color: Colors.white,
                      border: Border(
                        bottom: BorderSide(color: Colors.grey),
                      ),
                    ),
                    child: Row(
                      children: [
                        Text(
                          _getMenuTitle(selectedMenu),
                          style: const TextStyle(
                            fontSize: 24,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        const Spacer(),
                        IconButton(
                          icon: const Icon(Icons.notifications_outlined),
                          onPressed: () {},
                        ),
                      ],
                    ),
                  ),

                  // 컨텐츠 영역
                  Expanded(
                    child: _buildContent(selectedMenu),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildMenuSection(String title) {
    return Padding(
      padding: const EdgeInsets.fromLTRB(16, 16, 16, 8),
      child: Text(
        title,
        style: const TextStyle(
          color: Colors.white54,
          fontSize: 12,
          fontWeight: FontWeight.bold,
        ),
      ),
    );
  }

  Widget _buildMenuItem({
    required IconData icon,
    required String title,
    required String id,
    String? badge,
    Color? badgeColor,
    required VoidCallback onTap,
  }) {
    final isSelected = selectedMenu == id;

    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
      decoration: BoxDecoration(
        color: isSelected ? Colors.blue.withOpacity(0.2) : Colors.transparent,
        borderRadius: BorderRadius.circular(8),
      ),
      child: ListTile(
        leading: Icon(
          icon,
          color: isSelected ? Colors.blue : Colors.white70,
        ),
        title: Text(
          title,
          style: TextStyle(
            color: isSelected ? Colors.blue : Colors.white,
            fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
          ),
        ),
        trailing: badge != null
            ? Container(
          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
          decoration: BoxDecoration(
            color: badgeColor ?? Colors.red,
            borderRadius: BorderRadius.circular(12),
          ),
          child: Text(
            badge,
            style: const TextStyle(
              color: Colors.white,
              fontSize: 10,
              fontWeight: FontWeight.bold,
            ),
          ),
        )
            : null,
        onTap: onTap,
      ),
    );
  }

  String _getMenuTitle(String menuId) {
    switch (menuId) {
      case 'dashboard':
        return '대시보드';
      case 'publisher':
        return '출판사 관리';
      case 'all_books':
        return '전체 책 관리';
      case 'series':
        return '시리즈 관리';
      case 'users':
        return '회원 관리';
      case 'orders':
        return '주문 관리';
      case 'community':
        return '커뮤니티 관리';
      case 'banner':
        return '배너 관리';
      case 'news':
        return '뉴스/공지';
      case 'coupon':
        return '캐시/쿠폰';
      case 'stats':
        return '전체 통계';
      case 'settings':
        return '시스템 설정';
      default:
        return '대시보드';
    }
  }

  Widget _buildContent(String menuId) {
    switch (menuId) {
      case 'dashboard':
        return _buildDashboard();
      case 'users':
        return const Center(child: Text('회원 관리 화면 (준비중)'));
      case 'orders':
        return const Center(child: Text('주문 관리 화면 (준비중)'));
      case 'community':
        return const Center(child: Text('커뮤니티 관리 화면 (준비중)'));
      case 'banner':
        return const Center(child: Text('배너 관리 화면 (준비중)'));
      case 'news':
        return const Center(child: Text('뉴스/공지 관리 화면 (준비중)'));
      case 'coupon':
        return const Center(child: Text('캐시/쿠폰 관리 화면 (준비중)'));
      case 'stats':
        return const Center(child: Text('전체 통계 화면 (준비중)'));
      case 'settings':
        return const Center(child: Text('시스템 설정 화면 (준비중)'));
      default:
        return _buildDashboard();
    }
  }

  Widget _buildDashboard() {
    return Padding(
      padding: const EdgeInsets.all(20),
      child: GridView.count(
        crossAxisCount: 4,
        crossAxisSpacing: 16,
        mainAxisSpacing: 16,
        children: [
          _buildStatCard(
            icon: Icons.library_books,
            title: '출판사',
            count: '6',
            color: Colors.purple,
            onTap: () {
              Navigator.pushNamed(context, '/publishers_manage');  // 복수형!
            },
          ),
          _buildStatCard(
            icon: Icons.collections_bookmark,
            title: '전체 시리즈',
            count: '8',
            color: Colors.blue,
            onTap: () {
              Navigator.pushNamed(context, '/books_manage');
            },
          ),
          _buildStatCard(
            icon: Icons.book,
            title: '전체 권',
            count: '63',
            color: Colors.green,
          ),
          _buildStatCard(
            icon: Icons.people,
            title: '회원 수',
            count: '1,234',
            color: Colors.orange,
          ),
          _buildStatCard(
            icon: Icons.shopping_cart,
            title: '오늘 주문',
            count: '45',
            color: Colors.red,
          ),
          _buildStatCard(
            icon: Icons.attach_money,
            title: '오늘 매출',
            count: '₩1.2M',
            color: Colors.teal,
          ),
          _buildStatCard(
            icon: Icons.trending_up,
            title: '이번 달 매출',
            count: '₩28.5M',
            color: Colors.indigo,
          ),
          _buildStatCard(
            icon: Icons.visibility,
            title: '오늘 방문자',
            count: '892',
            color: Colors.amber,
          ),
        ],
      ),
    );
  }

  Widget _buildStatCard({
    required IconData icon,
    required String title,
    required String count,
    required Color color,
    VoidCallback? onTap,
  }) {
    return InkWell(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.all(20),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(12),
          boxShadow: [
            BoxShadow(
              color: Colors.grey.withOpacity(0.1),
              spreadRadius: 1,
              blurRadius: 5,
            ),
          ],
        ),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: color.withOpacity(0.1),
                borderRadius: BorderRadius.circular(12),
              ),
              child: Icon(
                icon,
                size: 32,
                color: color,
              ),
            ),
            const SizedBox(height: 12),
            Text(
              title,
              style: TextStyle(
                color: Colors.grey[600],
                fontSize: 14,
              ),
            ),
            const SizedBox(height: 4),
            Text(
              count,
              style: const TextStyle(
                fontSize: 24,
                fontWeight: FontWeight.bold,
              ),
            ),
          ],
        ),
      ),
    );
  }
}