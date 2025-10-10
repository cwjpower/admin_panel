import 'package:flutter/material.dart';
import '../services/api_service.dart';
import '../models/book.dart';
import '../utils/colors.dart';
import 'book_detail_screen.dart';

class SearchScreen extends StatefulWidget {
  @override
  _SearchScreenState createState() => _SearchScreenState();
}

class _SearchScreenState extends State<SearchScreen> {
  final TextEditingController _searchController = TextEditingController();
  List<Book> searchResults = [];
  bool isLoading = false;
  String searchQuery = '';

  // 검색 히스토리
  List<String> searchHistory = [];

  // 인기 검색어
  final List<String> popularSearches = [
    '원피스', '나루토', '진격의 거인', '귀멸의 칼날',
    '도쿄 리벤저스', '주술회전', '체인소맨', '하이큐'
  ];

  Future<void> _performSearch(String query) async {
    if (query.isEmpty) return;

    setState(() {
      isLoading = true;
      searchQuery = query;
    });

    try {
      final results = await ApiService.searchBooks(query);
      setState(() {
        searchResults = results;
        isLoading = false;
      });

      // 검색 히스토리 추가
      if (!searchHistory.contains(query)) {
        searchHistory.insert(0, query);
        if (searchHistory.length > 10) {
          searchHistory.removeLast();
        }
        // TODO: StorageService에 저장
      }
    } catch (e) {
      print('Search error: $e');
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
        leading: IconButton(
          icon: Icon(Icons.arrow_back, color: AppColors.textDark),
          onPressed: () => Navigator.pop(context),
        ),
        title: TextField(
          controller: _searchController,
          autofocus: true,
          decoration: InputDecoration(
            hintText: '만화 제목, 작가명 검색',
            border: InputBorder.none,
            suffixIcon: IconButton(
              icon: Icon(Icons.search, color: AppColors.primaryRed),
              onPressed: () => _performSearch(_searchController.text),
            ),
          ),
          onSubmitted: _performSearch,
        ),
      ),
      body: Column(
        children: [
          // 검색 전 화면
          if (searchQuery.isEmpty && !isLoading)
            Expanded(
              child: SingleChildScrollView(
                padding: EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // 최근 검색어
                    if (searchHistory.isNotEmpty) ...[
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text(
                            '최근 검색어',
                            style: TextStyle(
                              fontSize: 16,
                              fontWeight: FontWeight.bold,
                              color: AppColors.textDark,
                            ),
                          ),
                          TextButton(
                            onPressed: () {
                              setState(() {
                                searchHistory.clear();
                              });
                            },
                            child: Text(
                              '전체 삭제',
                              style: TextStyle(
                                color: AppColors.textGrey,
                                fontSize: 12,
                              ),
                            ),
                          ),
                        ],
                      ),
                      SizedBox(height: 8),
                      Wrap(
                        spacing: 8,
                        runSpacing: 8,
                        children: searchHistory.map((keyword) {
                          return InkWell(
                            onTap: () {
                              _searchController.text = keyword;
                              _performSearch(keyword);
                            },
                            child: Chip(
                              label: Text(keyword),
                              deleteIcon: Icon(Icons.close, size: 16),
                              onDeleted: () {
                                setState(() {
                                  searchHistory.remove(keyword);
                                });
                              },
                            ),
                          );
                        }).toList(),
                      ),
                      SizedBox(height: 24),
                    ],

                    // 인기 검색어
                    Text(
                      '인기 검색어',
                      style: TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                        color: AppColors.textDark,
                      ),
                    ),
                    SizedBox(height: 8),
                    Wrap(
                      spacing: 8,
                      runSpacing: 8,
                      children: popularSearches.map((keyword) {
                        return ActionChip(
                          label: Text(keyword),
                          onPressed: () {
                            _searchController.text = keyword;
                            _performSearch(keyword);
                          },
                        );
                      }).toList(),
                    ),

                    SizedBox(height: 24),

                    // 장르별 검색
                    Text(
                      '장르별 검색',
                      style: TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                        color: AppColors.textDark,
                      ),
                    ),
                    SizedBox(height: 12),
                    GridView.count(
                      shrinkWrap: true,
                      physics: NeverScrollableScrollPhysics(),
                      crossAxisCount: 3,
                      childAspectRatio: 2.5,
                      crossAxisSpacing: 8,
                      mainAxisSpacing: 8,
                      children: [
                        _buildGenreButton('소년만화', Colors.blue),
                        _buildGenreButton('청년만화', Colors.green),
                        _buildGenreButton('순정만화', Colors.pink),
                        _buildGenreButton('액션', Colors.red),
                        _buildGenreButton('판타지', Colors.purple),
                        _buildGenreButton('로맨스', Colors.pink[300]!),
                        _buildGenreButton('스포츠', Colors.orange),
                        _buildGenreButton('개그', Colors.yellow[700]!),
                        _buildGenreButton('스릴러', Colors.grey[800]!),
                      ],
                    ),
                  ],
                ),
              ),
            ),

          // 검색 중
          if (isLoading)
            Expanded(
              child: Center(
                child: CircularProgressIndicator(
                  color: AppColors.primaryRed,
                ),
              ),
            ),

          // 검색 결과
          if (!isLoading && searchQuery.isNotEmpty)
            Expanded(
              child: searchResults.isEmpty
                  ? Center(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(
                      Icons.search_off,
                      size: 80,
                      color: Colors.grey[300],
                    ),
                    SizedBox(height: 16),
                    Text(
                      '"$searchQuery" 검색 결과가 없습니다',
                      style: TextStyle(
                        color: AppColors.textGrey,
                      ),
                    ),
                  ],
                ),
              )
                  : ListView.builder(
                padding: EdgeInsets.all(16),
                itemCount: searchResults.length,
                itemBuilder: (context, index) {
                  return _buildSearchResultItem(searchResults[index]);
                },
              ),
            ),
        ],
      ),
    );
  }

  Widget _buildGenreButton(String genre, Color color) {
    return Material(
      color: color.withOpacity(0.1),
      borderRadius: BorderRadius.circular(8),
      child: InkWell(
        borderRadius: BorderRadius.circular(8),
        onTap: () {
          // TODO: 장르별 검색
        },
        child: Center(
          child: Text(
            genre,
            style: TextStyle(
              color: color,
              fontWeight: FontWeight.bold,
              fontSize: 12,
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildSearchResultItem(Book book) {
    return InkWell(
      onTap: () {
        Navigator.push(
          context,
          MaterialPageRoute(
            builder: (context) => BookDetailScreen(book: book),
          ),
        );
      },
      child: Container(
        margin: EdgeInsets.only(bottom: 16),
        child: Row(
          children: [
            // 책 표지
            Container(
              width: 60,
              height: 80,
              decoration: BoxDecoration(
                color: Colors.grey[300],
                borderRadius: BorderRadius.circular(4),
              ),
              child: Center(
                child: Icon(Icons.book, color: Colors.grey[600]),
              ),
            ),
            SizedBox(width: 12),
            // 책 정보
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    book.title,
                    style: TextStyle(
                      fontSize: 14,
                      fontWeight: FontWeight.bold,
                      color: AppColors.textDark,
                    ),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                  ),
                  SizedBox(height: 4),
                  Text(
                    book.author,
                    style: TextStyle(
                      fontSize: 12,
                      color: AppColors.textGrey,
                    ),
                  ),
                  SizedBox(height: 4),
                  Row(
                    children: [
                      Icon(Icons.star, size: 12, color: Colors.orange),
                      SizedBox(width: 2),
                      Text(
                        '${book.rating}',
                        style: TextStyle(fontSize: 11),
                      ),
                      SizedBox(width: 8),
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
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}