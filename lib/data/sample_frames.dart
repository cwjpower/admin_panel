// lib/data/sample_frames.dart

import '../models/comic_frame.dart';

/// 샘플 만화 데이터
/// 실제로는 frame.avf 파일에서 읽어옴
class SampleFrames {

  /// 테스트용 샘플 만화책
  /// 3페이지, 각 페이지당 3-4개 패널
  static ComicBook getSampleBook() {
    return ComicBook(
      pages: [
        // 페이지 1
        ComicPage(
          name: 'page1',
          frames: [
            // 상단 전체 패널
            ComicFrame(x: 0, y: 0, right: 800, bottom: 300),

            // 중간 왼쪽 패널
            ComicFrame(x: 0, y: 310, right: 390, bottom: 600),

            // 중간 오른쪽 패널
            ComicFrame(x: 410, y: 310, right: 800, bottom: 600),

            // 하단 전체 패널
            ComicFrame(x: 0, y: 610, right: 800, bottom: 1000),
          ],
        ),

        // 페이지 2
        ComicPage(
          name: 'page2',
          frames: [
            // 왼쪽 세로 긴 패널
            ComicFrame(x: 0, y: 0, right: 390, bottom: 700),

            // 오른쪽 상단 패널
            ComicFrame(x: 410, y: 0, right: 800, bottom: 340),

            // 오른쪽 하단 패널
            ComicFrame(x: 410, y: 350, right: 800, bottom: 700),

            // 하단 전체 패널
            ComicFrame(x: 0, y: 710, right: 800, bottom: 1000),
          ],
        ),

        // 페이지 3
        ComicPage(
          name: 'page3',
          frames: [
            // 상단 전체 패널 (큰 장면)
            ComicFrame(x: 0, y: 0, right: 800, bottom: 500),

            // 하단 좌측 패널
            ComicFrame(x: 0, y: 510, right: 250, bottom: 1000),

            // 하단 중앙 패널
            ComicFrame(x: 260, y: 510, right: 540, bottom: 1000),

            // 하단 우측 패널
            ComicFrame(x: 550, y: 510, right: 800, bottom: 1000),
          ],
        ),
      ],
    );
  }

  /// JSON 문자열로 변환 (디버깅용)
  static String toJsonString() {
    final book = getSampleBook();
    return book.toJson().toString();
  }

  /// 전체 패널 수 계산
  static int getTotalFrameCount() {
    final book = getSampleBook();
    return book.pages.fold(0, (sum, page) => sum + page.frames.length);
  }
}