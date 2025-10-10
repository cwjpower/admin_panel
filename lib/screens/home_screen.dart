import 'package:flutter/material.dart';
import '../utils/colors.dart';
import '../services/api_service.dart';
import '../models/book.dart';
import 'book_detail_screen.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({Key? key}) : super(key: key);

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  int _selectedIndex = 0;
  int _currentBannerIndex = 0;
  String _selectedNewsCategory = 'TOTAL';

  // API 데이터
  List<dynamic> banners = [];
  List<dynamic> allNews = [];
  List<Book> books = [];
  bool isLoading = true;

  @override
  void initState() {
    super.initState();
    loadData();
  }

  // API 데이터 로드
  Future<void> loadData() async {
    print('Loading data from API...');

    try {
      final bannersData = await ApiService.getBanners();
      final newsData = await ApiService.getPosts();
      final booksData = await ApiService.getBooks();

      setState(() {
        banners = bannersData;
        allNews = newsData;
        books = booksData.map((json) => Book.fromJson(json)).toList();
        isLoading = false;
      });

      print('Data loaded successfully!');
      print('Banners: ${banners.length}');
      print('News: ${allNews.length}');
      print('Books: ${books.length}');
    } catch (e) {
      print('Error loading data: $e');
      setState(() {
        isLoading = false;
      });
    }
  }

  // 선택된 카테고리의 뉴스 필터링
  List<dynamic> get filteredNews {
    if (_selectedNewsCategory == 'TOTAL') {
      return allNews;
    }
    return allNews.where((news) => news['category'] == _selectedNewsCategory).toList();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.black,
      appBar: AppBar(
        backgroundColor: AppColors.primary,
        elevation: 0,
        title: const Text(
          'HERO COMICS',
          style: TextStyle(
            color: Colors.black,
            fontWeight: FontWeight.bold,
            fontSize: 20,
          ),
        ),
        actions: [
          IconButton(
            icon: const Icon(Icons.search, color: Colors.black),
            onPressed: () {
              ScaffoldMessenger.of(context).showSnackBar(
                const SnackBar(content: Text('검색 기능은 준비 중입니다.')),
              );
            },
          ),
          IconButton(
            icon: const Icon(Icons.account_circle, color: Colors.black),
            onPressed: () {},
          ),
          Stack(
            children: [
              IconButton(
                icon: const Icon(Icons.shopping_cart, color: Colors.black),
                onPressed: () {
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(content: Text('장바구니 기능은 준비 중입니다.')),
                  );
                },
              ),
              Positioned(
                right: 8,
                top: 8,
                child: Container(
                  padding: const EdgeInsets.all(4),
                  decoration: const BoxDecoration(
                    color: Colors.red,
                    shape: BoxShape.circle,
                  ),
                  child: const Text(
                    '3',
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: 10,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ),
              ),
            ],
          ),
        ],
      ),
      body: isLoading
          ? const Center(
        child: CircularProgressIndicator(
          color: AppColors.primary,
        ),
      )
          : _selectedIndex == 0
          ? _buildHomeContent()
          : _selectedIndex == 1
          ? _buildBooksContent()
          : _selectedIndex == 2
          ? _buildCommunityContent()
          : _buildMyPageContent(),
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _selectedIndex,
        onTap: (index) {
          setState(() {
            _selectedIndex = index;
          });
        },
        type: BottomNavigationBarType.fixed,
        backgroundColor: Colors.grey[900],
        selectedItemColor: AppColors.primary,
        unselectedItemColor: Colors.grey,
        items: const [
          BottomNavigationBarItem(
            icon: Icon(Icons.home),
            label: '홈',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.book),
            label: '도서',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.chat_bubble),
            label: '커뮤니티',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.person),
            label: '마이',
          ),
        ],
      ),
    );
  }

  Widget _buildHomeContent() {
    return SingleChildScrollView(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          _buildBannerSlider(),
          _buildNoticeSection(),
          _buildNewsSection(),
          _buildNewBooksSection(),
          const SizedBox(height: 80),
        ],
      ),
    );
  }

  Widget _buildBannerSlider() {
    if (banners.isEmpty) {
      return Container(
        height: 200,
        color: Colors.grey[800],
        child: const Center(
          child: Text(
            '배너 없음',
            style: TextStyle(color: Colors.white),
          ),
        ),
      );
    }

    return Container(
      height: 200,
      child: Stack(
        children: [
          PageView.builder(
            itemCount: banners.length,
            onPageChanged: (index) {
              setState(() {
                _currentBannerIndex = index;
              });
            },
            itemBuilder: (context, index) {
              final banner = banners[index];
              return InkWell(
                onTap: () {
                  print('Banner clicked: ${banner['title']}');
                  ScaffoldMessenger.of(context).showSnackBar(
                    SnackBar(
                      content: Text('${banner['title']} 클릭!'),
                      duration: const Duration(seconds: 1),
                    ),
                  );
                },
                child: Container(
                  decoration: BoxDecoration(
                    gradient: LinearGradient(
                      begin: Alignment.topCenter,
                      end: Alignment.bottomCenter,
                      colors: [
                        Colors.amber[700]!,
                        Colors.red[900]!,
                      ],
                    ),
                  ),
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      const Icon(
                        Icons.menu_book,
                        size: 60,
                        color: Colors.white,
                      ),
                      const SizedBox(height: 16),
                      Text(
                        banner['title'] ?? '',
                        style: const TextStyle(
                          fontSize: 24,
                          fontWeight: FontWeight.bold,
                          color: Colors.white,
                        ),
                      ),
                      const SizedBox(height: 8),
                      Text(
                        banner['subtitle'] ?? '',
                        style: const TextStyle(
                          fontSize: 14,
                          color: Colors.white,
                        ),
                      ),
                    ],
                  ),
                ),
              );
            },
          ),
          Positioned(
            bottom: 10,
            left: 0,
            right: 0,
            child: Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: List.generate(
                banners.length,
                    (index) => Container(
                  margin: const EdgeInsets.symmetric(horizontal: 4),
                  width: 8,
                  height: 8,
                  decoration: BoxDecoration(
                    shape: BoxShape.circle,
                    color: _currentBannerIndex == index
                        ? Colors.white
                        : Colors.white.withOpacity(0.4),
                  ),
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildNoticeSection() {
    final notices = allNews.where((news) => news['category'] == 'NOTICE').toList();

    if (notices.isEmpty) {
      return const SizedBox.shrink();
    }

    return InkWell(
      onTap: () {
        print('Notice clicked: ${notices.first['title']}');
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('${notices.first['title']} 클릭!'),
            duration: const Duration(seconds: 1),
          ),
        );
      },
      child: Container(
        margin: const EdgeInsets.all(16),
        padding: const EdgeInsets.all(12),
        decoration: BoxDecoration(
          color: Colors.grey[900],
          borderRadius: BorderRadius.circular(8),
        ),
        child: Row(
          children: [
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
              decoration: BoxDecoration(
                color: Colors.red,
                borderRadius: BorderRadius.circular(4),
              ),
              child: const Text(
                'NOTICE',
                style: TextStyle(
                  color: Colors.white,
                  fontSize: 12,
                  fontWeight: FontWeight.bold,
                ),
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Text(
                notices.first['title'] ?? '',
                style: const TextStyle(
                  color: Colors.white,
                  fontSize: 14,
                ),
                maxLines: 1,
                overflow: TextOverflow.ellipsis,
              ),
            ),
            Text(
              notices.first['created_at']?.substring(0, 10) ?? '',
              style: TextStyle(
                color: Colors.grey[500],
                fontSize: 12,
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildNewsSection() {
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text(
                'NEWS',
                style: TextStyle(
                  color: Colors.white,
                  fontSize: 20,
                  fontWeight: FontWeight.bold,
                ),
              ),
              TextButton(
                onPressed: () {
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(content: Text('뉴스 전체보기 기능은 준비 중입니다.')),
                  );
                },
                child: const Text(
                  'MORE',
                  style: TextStyle(
                    color: AppColors.primary,
                    fontSize: 14,
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),
          SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            child: Row(
              children: [
                _buildNewsCategoryButton('TOTAL'),
                _buildNewsCategoryButton('MARVEL'),
                _buildNewsCategoryButton('DC'),
                _buildNewsCategoryButton('IMAGE'),
              ],
            ),
          ),
          const SizedBox(height: 16),
          SizedBox(
            height: 250,
            child: filteredNews.isEmpty
                ? Center(
              child: Text(
                '뉴스가 없습니다',
                style: TextStyle(color: Colors.grey[600]),
              ),
            )
                : ListView.builder(
              scrollDirection: Axis.horizontal,
              itemCount: filteredNews.length,
              itemBuilder: (context, index) {
                final news = filteredNews[index];
                return InkWell(
                  onTap: () {
                    print('News clicked: ${news['title']}');
                    ScaffoldMessenger.of(context).showSnackBar(
                      SnackBar(
                        content: Text('${news['title']} 클릭!'),
                        duration: const Duration(seconds: 1),
                      ),
                    );
                  },
                  child: Container(
                    width: 200,
                    margin: const EdgeInsets.only(right: 16),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Container(
                          height: 150,
                          decoration: BoxDecoration(
                            color: Colors.grey[800],
                            borderRadius: BorderRadius.circular(8),
                          ),
                          child: const Center(
                            child: Icon(
                              Icons.article,
                              size: 50,
                              color: AppColors.primary,
                            ),
                          ),
                        ),
                        const SizedBox(height: 8),
                        Container(
                          padding: const EdgeInsets.symmetric(
                              horizontal: 6, vertical: 2),
                          decoration: BoxDecoration(
                            color: Colors.grey[800],
                            borderRadius: BorderRadius.circular(4),
                          ),
                          child: Text(
                            news['category'] ?? '',
                            style: TextStyle(
                              color: Colors.grey[400],
                              fontSize: 10,
                            ),
                          ),
                        ),
                        const SizedBox(height: 4),
                        Text(
                          news['title'] ?? '',
                          style: const TextStyle(
                            color: Colors.white,
                            fontSize: 14,
                            fontWeight: FontWeight.bold,
                          ),
                          maxLines: 2,
                          overflow: TextOverflow.ellipsis,
                        ),
                        const SizedBox(height: 4),
                        Text(
                          news['created_at']?.substring(0, 10) ?? '',
                          style: TextStyle(
                            color: Colors.grey[600],
                            fontSize: 12,
                          ),
                        ),
                      ],
                    ),
                  ),
                );
              },
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildNewsCategoryButton(String category) {
    final isSelected = _selectedNewsCategory == category;
    return GestureDetector(
      onTap: () {
        setState(() {
          _selectedNewsCategory = category;
        });
        print('Category changed to: $category');
      },
      child: Container(
        margin: const EdgeInsets.only(right: 8),
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
        decoration: BoxDecoration(
          color: isSelected ? AppColors.primary : Colors.grey[800],
          borderRadius: BorderRadius.circular(20),
        ),
        child: Text(
          category,
          style: TextStyle(
            color: isSelected ? Colors.black : Colors.white,
            fontSize: 14,
            fontWeight: FontWeight.bold,
          ),
        ),
      ),
    );
  }

  Widget _buildNewBooksSection() {
    return Container(
      margin: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text(
                'NEW BOOKS',
                style: TextStyle(
                  color: Colors.white,
                  fontSize: 20,
                  fontWeight: FontWeight.bold,
                ),
              ),
              TextButton(
                onPressed: () {
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(content: Text('도서 전체보기 기능은 준비 중입니다.')),
                  );
                },
                child: const Text(
                  'MORE',
                  style: TextStyle(
                    color: AppColors.primary,
                    fontSize: 14,
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 16),
          books.isEmpty
              ? Center(
            child: Text(
              '책이 없습니다',
              style: TextStyle(color: Colors.grey[600]),
            ),
          )
              : GridView.builder(
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
              crossAxisCount: 3,
              childAspectRatio: 0.6,
              crossAxisSpacing: 12,
              mainAxisSpacing: 12,
            ),
            itemCount: books.length > 6 ? 6 : books.length,
            itemBuilder: (context, index) {
              final book = books[index];
              return InkWell(
                onTap: () {
                  print('Book clicked: ${book.title}');
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) => BookDetailScreen(
                        book: book,
                      ),
                    ),
                  );
                },
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Expanded(
                      child: Container(
                        decoration: BoxDecoration(
                          color: Colors.grey[800],
                          borderRadius: BorderRadius.circular(8),
                        ),
                        child: Center(
                          child: Icon(
                            Icons.book,
                            size: 40,
                            color: Colors.grey[600],
                          ),
                        ),
                      ),
                    ),
                    const SizedBox(height: 8),
                    Text(
                      book.title,
                      style: const TextStyle(
                        color: Colors.white,
                        fontSize: 12,
                        fontWeight: FontWeight.bold,
                      ),
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                    ),
                    const SizedBox(height: 4),
                    Text(
                      book.author ?? '',
                      style: TextStyle(
                        color: Colors.grey[500],
                        fontSize: 10,
                      ),
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                    ),
                  ],
                ),
              );
            },
          ),
        ],
      ),
    );
  }

  Widget _buildBooksContent() {
    return const Center(
      child: Text(
        '도서 화면',
        style: TextStyle(color: Colors.white, fontSize: 24),
      ),
    );
  }

  Widget _buildCommunityContent() {
    return const Center(
      child: Text(
        '커뮤니티 화면',
        style: TextStyle(color: Colors.white, fontSize: 24),
      ),
    );
  }

  Widget _buildMyPageContent() {
    return const Center(
      child: Text(
        '마이페이지 화면',
        style: TextStyle(color: Colors.white, fontSize: 24),
      ),
    );
  }
}