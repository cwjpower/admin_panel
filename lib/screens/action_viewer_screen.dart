// lib/screens/action_viewer_screen.dart

import 'package:flutter/material.dart';
import '../models/comic_frame.dart';
import '../data/sample_frames.dart';

class ActionViewerScreen extends StatefulWidget {
  const ActionViewerScreen({Key? key}) : super(key: key);

  @override
  State<ActionViewerScreen> createState() => _ActionViewerScreenState();
}

class _ActionViewerScreenState extends State<ActionViewerScreen>
    with SingleTickerProviderStateMixin {

  late ComicBook comicBook;
  late TransformationController _transformController;
  late AnimationController _animationController;
  Animation<Matrix4>? _animation;

  int currentPageIndex = 0;
  int currentFrameIndex = 0;

  bool showUI = true; // UI 표시 여부

  @override
  void initState() {
    super.initState();
    comicBook = SampleFrames.getSampleBook();
    _transformController = TransformationController();
    _animationController = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 400),
    );

    // 초기 프레임으로 이동
    WidgetsBinding.instance.addPostFrameCallback((_) {
      _animateToFrame(0, 0, animated: false);
    });
  }

  @override
  void dispose() {
    _transformController.dispose();
    _animationController.dispose();
    super.dispose();
  }

  /// 특정 프레임으로 애니메이션 이동
  void _animateToFrame(int pageIndex, int frameIndex, {bool animated = true}) {
    if (pageIndex >= comicBook.pages.length) return;

    final page = comicBook.pages[pageIndex];
    if (frameIndex >= page.frames.length) return;

    setState(() {
      currentPageIndex = pageIndex;
      currentFrameIndex = frameIndex;
    });

    final frame = page.frames[frameIndex];
    final size = MediaQuery.of(context).size;

    // 화면 크기에 맞춰 스케일 계산
    final scaleX = size.width / frame.width;
    final scaleY = size.height / frame.height;
    final scale = scaleX < scaleY ? scaleX : scaleY;

    // 중앙 정렬을 위한 오프셋 계산
    final translationX = -frame.x * scale + (size.width - frame.width * scale) / 2;
    final translationY = -frame.y * scale + (size.height - frame.height * scale) / 2;

    final targetMatrix = Matrix4.identity()
      ..translate(translationX, translationY)
      ..scale(scale);

    if (!animated) {
      _transformController.value = targetMatrix;
      return;
    }

    // 부드러운 애니메이션
    _animation = Matrix4Tween(
      begin: _transformController.value,
      end: targetMatrix,
    ).animate(CurvedAnimation(
      parent: _animationController,
      curve: Curves.easeInOut,
    ));

    _animationController.reset();
    _animationController.forward();
  }

  /// 다음 프레임으로
  void _nextFrame() {
    final currentPage = comicBook.pages[currentPageIndex];

    if (currentFrameIndex < currentPage.frames.length - 1) {
      // 같은 페이지의 다음 프레임
      _animateToFrame(currentPageIndex, currentFrameIndex + 1);
    } else if (currentPageIndex < comicBook.pages.length - 1) {
      // 다음 페이지의 첫 프레임
      _animateToFrame(currentPageIndex + 1, 0);
    } else {
      // 마지막 프레임
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('마지막 페이지입니다'), duration: Duration(seconds: 1)),
      );
    }
  }

  /// 이전 프레임으로
  void _previousFrame() {
    if (currentFrameIndex > 0) {
      // 같은 페이지의 이전 프레임
      _animateToFrame(currentPageIndex, currentFrameIndex - 1);
    } else if (currentPageIndex > 0) {
      // 이전 페이지의 마지막 프레임
      final prevPage = comicBook.pages[currentPageIndex - 1];
      _animateToFrame(currentPageIndex - 1, prevPage.frames.length - 1);
    } else {
      // 첫 프레임
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('첫 페이지입니다'), duration: Duration(seconds: 1)),
      );
    }
  }

  /// UI 토글
  void _toggleUI() {
    setState(() {
      showUI = !showUI;
    });
  }

  /// 터치 위치에 따른 동작
  void _handleTap(TapDownDetails details) {
    final width = MediaQuery.of(context).size.width;
    final x = details.localPosition.dx;

    if (x < width / 3) {
      // 왼쪽 1/3: 이전 프레임
      _previousFrame();
    } else if (x > width * 2 / 3) {
      // 오른쪽 1/3: 다음 프레임
      _nextFrame();
    } else {
      // 중앙 1/3: UI 토글
      _toggleUI();
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.black,
      body: Stack(
        children: [
          // 만화 뷰어
          GestureDetector(
            onTapDown: _handleTap,
            child: AnimatedBuilder(
              animation: _animationController,
              builder: (context, child) {
                if (_animation != null) {
                  _transformController.value = _animation!.value;
                }
                return InteractiveViewer(
                  transformationController: _transformController,
                  minScale: 0.5,
                  maxScale: 4.0,
                  panEnabled: false, // 터치로 패닝 비활성화
                  scaleEnabled: false, // 핀치 줌 비활성화
                  child: child!,
                );
              },
              child: _buildComicImage(),
            ),
          ),

          // 상단 UI (타이틀, 페이지 정보)
          if (showUI)
            Positioned(
              top: 0,
              left: 0,
              right: 0,
              child: _buildTopBar(),
            ),

          // 하단 UI (네비게이션)
          if (showUI)
            Positioned(
              bottom: 0,
              left: 0,
              right: 0,
              child: _buildBottomBar(),
            ),
        ],
      ),
    );
  }

  /// 만화 이미지 (샘플은 컬러 박스로 대체)
  Widget _buildComicImage() {
    final page = comicBook.pages[currentPageIndex];

    return Container(
      width: 800,
      height: 1000,
      color: Colors.grey[900],
      child: Stack(
        children: [
          // 페이지 배경
          Center(
            child: Text(
              'Page ${currentPageIndex + 1}',
              style: const TextStyle(
                color: Colors.white30,
                fontSize: 48,
                fontWeight: FontWeight.bold,
              ),
            ),
          ),

          // 패널 경계선 표시 (디버깅용)
          ...page.frames.asMap().entries.map((entry) {
            final index = entry.key;
            final frame = entry.value;
            final isCurrentFrame = index == currentFrameIndex;

            return Positioned(
              left: frame.x,
              top: frame.y,
              width: frame.width,
              height: frame.height,
              child: Container(
                decoration: BoxDecoration(
                  border: Border.all(
                    color: isCurrentFrame ? Colors.red : Colors.blue.withOpacity(0.5),
                    width: isCurrentFrame ? 3 : 1,
                  ),
                ),
                child: Center(
                  child: Text(
                    'Frame ${index + 1}',
                    style: TextStyle(
                      color: isCurrentFrame ? Colors.red : Colors.blue,
                      fontSize: 20,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ),
              ),
            );
          }),
        ],
      ),
    );
  }

  /// 상단 바
  Widget _buildTopBar() {
    return Container(
      padding: EdgeInsets.only(
        top: MediaQuery.of(context).padding.top + 8,
        left: 16,
        right: 16,
        bottom: 8,
      ),
      decoration: BoxDecoration(
        gradient: LinearGradient(
          begin: Alignment.topCenter,
          end: Alignment.bottomCenter,
          colors: [
            Colors.black.withOpacity(0.7),
            Colors.transparent,
          ],
        ),
      ),
      child: Row(
        children: [
          IconButton(
            icon: const Icon(Icons.arrow_back, color: Colors.white),
            onPressed: () => Navigator.pop(context),
          ),
          const SizedBox(width: 8),
          Expanded(
            child: Text(
              'Action Viewer Demo',
              style: const TextStyle(
                color: Colors.white,
                fontSize: 18,
                fontWeight: FontWeight.bold,
              ),
            ),
          ),
          Text(
            'Page ${currentPageIndex + 1}/${comicBook.pages.length}',
            style: const TextStyle(color: Colors.white70),
          ),
        ],
      ),
    );
  }

  /// 하단 바
  Widget _buildBottomBar() {
    final currentPage = comicBook.pages[currentPageIndex];

    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        gradient: LinearGradient(
          begin: Alignment.bottomCenter,
          end: Alignment.topCenter,
          colors: [
            Colors.black.withOpacity(0.7),
            Colors.transparent,
          ],
        ),
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          // 프레임 진행 표시
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: List.generate(
              currentPage.frames.length,
                  (index) => Container(
                margin: const EdgeInsets.symmetric(horizontal: 4),
                width: 8,
                height: 8,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: index == currentFrameIndex
                      ? Colors.red
                      : Colors.white.withOpacity(0.5),
                ),
              ),
            ),
          ),
          const SizedBox(height: 16),

          // 컨트롤 버튼
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceEvenly,
            children: [
              IconButton(
                icon: const Icon(Icons.skip_previous, color: Colors.white),
                iconSize: 32,
                onPressed: _previousFrame,
              ),
              Text(
                'Frame ${currentFrameIndex + 1}/${currentPage.frames.length}',
                style: const TextStyle(color: Colors.white, fontSize: 16),
              ),
              IconButton(
                icon: const Icon(Icons.skip_next, color: Colors.white),
                iconSize: 32,
                onPressed: _nextFrame,
              ),
            ],
          ),

          // 도움말
          const SizedBox(height: 8),
          const Text(
            '← 이전 | 중앙 탭하여 UI 숨기기 | 다음 →',
            style: TextStyle(color: Colors.white60, fontSize: 12),
          ),
        ],
      ),
    );
  }
}