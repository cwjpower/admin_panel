import 'package:flutter/material.dart';
import '../services/api_service.dart';
import '../models/book.dart';
import '../models/banner.dart' as banner_model;
import '../models/news.dart';
import '../utils/colors.dart';
import 'book_detail_screen.dart';
import 'search_screen.dart';
import 'category_screen.dart';

class HomeScreen extends StatefulWidget {
  @override
  _HomeScreenState createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  final PageController _pageController = PageController();
  int _currentBannerIndex = 0;

  List<banner_model.Banner> banners = [];
  List<Book> allBooks = [];
  List<Book> marvelBooks = [];
  List<Book> dcBooks = [];
  List<News> newsList = [];

  bool isLoading = true;
  String selectedNewsCategory = 'TOTAL';

  // GitHub 이미지 URL들
  final List<String> bookCovers = [
    'https://raw.githubusercontent.com/cwjpower/herocomics-app/main/_tmp_extract/graphics_data/book_cover1.jpg',
    'https://raw.githubusercontent.com/cwjpower/herocomics-app/main/_tmp_extract/graphics_data/book_cover2.jpg',
    'https://raw.githubusercontent.com/cwjpower/herocomics-app/main/_tmp_extract/graphics_data/book_cover3.jpg',
    'https://raw.githubusercontent.com/cwjpower/herocomics-app/main/_tmp_extract/graphics_data/book_cover4.jpg',
    'https://raw.githubusercontent.com/cwjpower/herocomics-app/main/_tmp_extract/graphics_data/book_cover5.jpg',
  ];

  final List<String> bannerImages = [
    'https://raw.githubusercontent.com/cwjpower/herocomics-app/main/_tmp_extract/graphics_data/banner1.jpg',
    'https://raw.githubusercontent.com/cwjpower/herocomics-app/main/_tmp_extract/graphics_data/banner2.jpg',
    'https://raw.githubusercontent.com/cwjpower/herocomics-app/main/_tmp_extract/graphics_data/banner3.jpg',
  ];

  @override
  void initState() {
    super.initState();
    _loadData();
  }

  Future<void> _loadData() async {
    setState(() {
      isLoading = true;
    });

    try {
      final fetchedBanners = await ApiService.fetchBanners();
      final fetchedBooks = await ApiService.fetchBooks();
      final fetchedMarvel = await ApiService.fetchBooks(category: 'marvel');
      final fetchedDC = await ApiService.fetchBooks(category: 'dc');
      final fetchedNews = await ApiService.fetchNews();

      setState(() {
        banners = fetchedBanners;
        allBooks = fetchedBooks;
        marvelBooks = fetchedMarvel;
        dcBooks = fetchedDC;
        newsList = fetchedNews;
        isLoading = false;
      });
    } catch (e) {
      print('Error loading data: $e');
      setState(() {
        isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        title: Text(
          'HERO COMICS',
          style: TextStyle(
            color: AppColors.primaryRed,
            fontWeight: FontWeight.bold,
            fontSize: 24,
          ),
        ),
        actions: [
          IconButton(
            icon: Icon(Icons.search, color: AppColors.textDark),
            onPressed: () {
              Navigator.push(
                context,
                MaterialPageRoute(builder: (context) => SearchScreen()),
              );
            },
          ),
          IconButton(
            icon: Stack(
              children: [
                Icon(Icons.shopping_cart, color: AppColors.textDark),
                Positioned(
                  right: 0,
                  top: 0,
                  child: Container(
                    padding: EdgeInsets.all(2),
                    decoration: BoxDecoration(
                      color: AppColors.primaryRed,
                      borderRadius: BorderRadius.circular(10),
                    ),
                    constraints: BoxConstraints(
                      minWidth: 16,
                      minHeight: 16,
                    ),
                    child: Text(
                      '0',
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: 10,
                      ),
                      textAlign: TextAlign.center,
                    ),
                  ),
                ),
              ],
            ),
            onPressed: () {
              // TODO: 장바구니 화면으로 이동
            },
          ),
        ],
      ),
      body: isLoading
          ? Center(child: CircularProgressIndicator(color: AppColors.primaryRed))
          : RefreshIndicator(
        color: AppColors.primaryRed,
        onRefresh: _loadData,
        child: SingleChildScrollView(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // 배너 슬라이더
              if (banners.isNotEmpty) _buildBannerSlider(),

              // 카테고리 바로가기
              _buildCategoryShortcuts(),

              // NEWS 섹션
              _buildNewsSection(),

              // NEW BOOKS 섹션
              _buildBooksSection('NEW BOOKS', allBooks, showAll: true),

              // MARVEL BOOKS 섹션
              if (marvelBooks.isNotEmpty)
                _buildBooksSection('MARVEL BOOKS', marvelBooks),

              // DC BOOKS 섹션
              if (dcBooks.isNotEmpty)
                _buildBooksSection('DC BOOKS', dcBooks),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildBannerSlider() {
    return Container(
      height: 200,
      child: Stack(
        children: [
          PageView.builder(
            controller: _pageController,
            itemCount: banners.length,
            onPageChanged: (index) {
              setState(() {
                _currentBannerIndex = index;
              });
            },
            itemBuilder: (context, index) {
              final colors = [
                [Colors.red[400]!, Colors.red[700]!],
                [Colors.blue[400]!, Colors.blue[700]!],
                [Colors.green[400]!, Colors.green[700]!],
              ];

              return Container(
                margin: EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(12),
                  gradient: LinearGradient(
                    colors: colors[index % 3],
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                  ),
                ),
                child: Stack(
                  children: [
                    Positioned(
                      bottom: 20,
                      left: 20,
                      child: Text(
                        banners[index].title,
                        style: TextStyle(
                          color: Colors.white,
                          fontSize: 24,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ],
                ),
              );
            },
          ),
          Positioned(
            bottom: 20,
            right: 30,
            child: Row(
              children: List.generate(
                banners.length,
                    (index) => Container(
                  margin: EdgeInsets.symmetric(horizontal: 4),
                  width: 8,
                  height: 8,
                  decoration: BoxDecoration(
                    shape: BoxShape.circle,
                    color: _currentBannerIndex == index
                        ? Colors.white
                        : Colors.white.withOpacity(0.5),
                  ),
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildCategoryShortcuts() {
    final categories = [
      {'title': '소년만화', 'icon': Icons.boy, 'color': Colors.blue},
      {'title': '청년만화', 'icon': Icons.man, 'color': Colors.green},
      {'title': '순정만화', 'icon': Icons.favorite, 'color': Colors.pink},
      {'title': '무료만화', 'icon': Icons.card_giftcard, 'color': Colors.orange},
    ];

    return Container(
      padding: EdgeInsets.all(16),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceAround,
        children: categories.map((cat) {
          return GestureDetector(
            onTap: () {
              Navigator.push(
                context,
                MaterialPageRoute(builder: (context) => CategoryScreen()),
              );
            },
            child: Column(
              children: [
                Container(
                  width: 60,
                  height: 60,
                  decoration: BoxDecoration(
                    color: (cat['color'] as Color).withOpacity(0.1),
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: Icon(
                    cat['icon'] as IconData,
                    color: cat['color'] as Color,
                    size: 30,
                  ),
                ),
                SizedBox(height: 8),
                Text(
                  cat['title'] as String,
                  style: TextStyle(
                    fontSize: 12,
                    color: AppColors.textDark,
                  ),
                ),
              ],
            ),
          );
        }).toList(),
      ),
    );
  }

  Widget _buildNewsSection() {
    return Container(
      padding: EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                'NEWS',
                style: TextStyle(
                  fontSize: 20,
                  fontWeight: FontWeight.bold,
                  color: AppColors.textDark,
                ),
              ),
              Row(
                children: ['TOTAL', 'MARVEL', 'DC', 'IMAGE'].map((category) {
                  bool isSelected = selectedNewsCategory == category;
                  return GestureDetector(
                    onTap: () async {
                      setState(() {
                        selectedNewsCategory = category;
                      });
                      newsList = await ApiService.fetchNews(
                        category: category == 'TOTAL' ? null : category,
                      );
                      setState(() {});
                    },
                    child: Container(
                      margin: EdgeInsets.only(left: 8),
                      padding: EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                      decoration: BoxDecoration(
                        color: isSelected ? AppColors.primaryRed : Colors.grey[200],
                        borderRadius: BorderRadius.circular(16),
                      ),
                      child: Text(
                        category,
                        style: TextStyle(
                          color: isSelected ? Colors.white : AppColors.textDark,
                          fontSize: 12,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  );
                }).toList(),
              ),
            ],
          ),
          SizedBox(height: 12),
          ...newsList.take(3).map((news) => _buildNewsItem(news)).toList(),
        ],
      ),
    );
  }

  Widget _buildNewsItem(News news) {
    final colors = [Colors.red, Colors.blue, Colors.green];
    final color = colors[news.id % 3];

    return Container(
      margin: EdgeInsets.only(bottom: 12),
      padding: EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.grey[50],
        borderRadius: BorderRadius.circular(8),
      ),
      child: Row(
        children: [
          Container(
            width: 80,
            height: 60,
            decoration: BoxDecoration(
              color: color.withOpacity(0.1),
              borderRadius: BorderRadius.circular(8),
            ),
            child: Center(
              child: Icon(Icons.article, size: 24, color: color),
            ),
          ),
          SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Container(
                  padding: EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                  decoration: BoxDecoration(
                    color: AppColors.primaryRed,
                    borderRadius: BorderRadius.circular(4),
                  ),
                  child: Text(
                    news.category,
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: 10,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ),
                SizedBox(height: 4),
                Text(
                  news.title,
                  style: TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.bold,
                    color: AppColors.textDark,
                  ),
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildBooksSection(String title, List<Book> books, {bool showAll = false}) {
    if (books.isEmpty) return SizedBox.shrink();

    return Container(
      padding: EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                title,
                style: TextStyle(
                  fontSize: 20,
                  fontWeight: FontWeight.bold,
                  color: AppColors.textDark,
                ),
              ),
              InkWell(
                onTap: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(builder: (context) => CategoryScreen()),
                  );
                },
                child: Text(
                  'See all',
                  style: TextStyle(
                    color: AppColors.primaryRed,
                    fontSize: 14,
                  ),
                ),
              ),
            ],
          ),
          SizedBox(height: 12),
          Container(
            height: 220,
            child: ListView.builder(
              scrollDirection: Axis.horizontal,
              itemCount: books.length,
              itemBuilder: (context, index) {
                return _buildBookItem(books[index], index);
              },
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildBookItem(Book book, int index) {
    final bookIdInt = int.tryParse(book.id) ?? 0;
    final colors = [
      Colors.red[400]!,
      Colors.blue[400]!,
      Colors.green[400]!,
      Colors.orange[400]!,
      Colors.purple[400]!,
    ];

    return GestureDetector(
      onTap: () {
        Navigator.push(
          context,
          MaterialPageRoute(
            builder: (context) => BookDetailScreen(book: book),
          ),
        );
      },
      child: Container(
        width: 120,
        margin: EdgeInsets.only(right: 12),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Container(
              height: 160,
              width: 120,
              decoration: BoxDecoration(
                color: colors[bookIdInt % 5],
                borderRadius: BorderRadius.circular(8),
              ),
              child: Center(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(Icons.book, color: Colors.white, size: 40),
                    SizedBox(height: 8),
                    Text(
                      book.title.split(' ').first,
                      style: TextStyle(
                        color: Colors.white,
                        fontWeight: FontWeight.bold,
                        fontSize: 12,
                      ),
                    ),
                  ],
                ),
              ),
            ),
            SizedBox(height: 8),
            Text(
              book.title,
              style: TextStyle(
                fontSize: 12,
                fontWeight: FontWeight.bold,
                color: AppColors.textDark,
              ),
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
            ),
            Text(
              book.author,
              style: TextStyle(
                fontSize: 10,
                color: Colors.grey[600],
              ),
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
            ),
            Text(
              '₩${book.price}',
              style: TextStyle(
                fontSize: 12,
                color: AppColors.primaryRed,
                fontWeight: FontWeight.bold,
              ),
            ),
          ],
        ),
      ),
    );
  }
}