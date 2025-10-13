import 'package:flutter/material.dart';
import 'dart:io';
import 'package:file_picker/file_picker.dart';
import 'package:image_picker/image_picker.dart';
import '../services/admin_api_service.dart';

class VolumeManageScreen extends StatefulWidget {
  final int seriesId;
  final String seriesTitle;

  const VolumeManageScreen({
    Key? key,
    required this.seriesId,
    required this.seriesTitle,
  }) : super(key: key);

  @override
  State<VolumeManageScreen> createState() => _VolumeManageScreenState();
}

class _VolumeManageScreenState extends State<VolumeManageScreen> {
  List<dynamic> volumes = [];
  bool isLoading = false;

  @override
  void initState() {
    super.initState();
    _loadVolumes();
  }

  Future<void> _loadVolumes() async {
    setState(() => isLoading = true);
    final result = await AdminApiService.getSeriesDetail(widget.seriesId);

    if (result['success'] == true) {
      setState(() {
        volumes = result['volumes'] ?? [];
        isLoading = false;
      });
    } else {
      setState(() => isLoading = false);
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('로드 실패: ${result['message']}')),
        );
      }
    }
  }

  // 커버 이미지 업로드
  Future<void> _uploadCover(int volumeId) async {
    final ImagePicker picker = ImagePicker();
    final XFile? image = await picker.pickImage(source: ImageSource.gallery);

    if (image == null) return;

    // 로딩 표시
    if (!mounted) return;
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (context) => Center(child: CircularProgressIndicator()),
    );

    final result = await AdminApiService.uploadVolumeCover(
      volumeId,
      File(image.path),
    );

    if (!mounted) return;
    Navigator.pop(context); // 로딩 닫기

    if (result['code'] == 0) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('커버 업로드 성공!')),
      );
      _loadVolumes(); // 새로고침
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('업로드 실패: ${result['msg']}')),
      );
    }
  }

  // 페이지 ZIP 업로드
  Future<void> _uploadPages(int volumeId) async {
    FilePickerResult? result = await FilePicker.platform.pickFiles(
      type: FileType.custom,
      allowedExtensions: ['zip'],
    );

    if (result == null || result.files.single.path == null) return;

    // 로딩 표시
    if (!mounted) return;
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (context) => Center(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            CircularProgressIndicator(),
            SizedBox(height: 16),
            Text('ZIP 파일 업로드 중...', style: TextStyle(color: Colors.white)),
          ],
        ),
      ),
    );

    final uploadResult = await AdminApiService.uploadPagesZip(
      volumeId,
      File(result.files.single.path!),
    );

    if (!mounted) return;
    Navigator.pop(context); // 로딩 닫기

    if (uploadResult['code'] == 0) {
      final pageCount = uploadResult['data']['page_count'];
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('페이지 업로드 성공! ($pageCount 페이지)')),
      );
      _loadVolumes(); // 새로고침
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('업로드 실패: ${uploadResult['msg']}')),
      );
    }
  }

  Future<void> _showAddVolumeDialog() async {
    final titleController = TextEditingController();
    final volumeNumberController = TextEditingController();
    final priceController = TextEditingController();

    final result = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        title: Text('권 추가'),
        content: SingleChildScrollView(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              TextField(
                controller: titleController,
                decoration: InputDecoration(labelText: '제목'),
              ),
              TextField(
                controller: volumeNumberController,
                decoration: InputDecoration(labelText: '권 번호'),
                keyboardType: TextInputType.number,
              ),
              TextField(
                controller: priceController,
                decoration: InputDecoration(labelText: '가격'),
                keyboardType: TextInputType.number,
              ),
            ],
          ),
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: Text('취소'),
          ),
          ElevatedButton(
            onPressed: () async {
              final title = titleController.text.trim();
              final volumeNumber = int.tryParse(volumeNumberController.text) ?? 0;
              final price = int.tryParse(priceController.text) ?? 0;

              if (title.isEmpty) {
                ScaffoldMessenger.of(context).showSnackBar(
                  SnackBar(content: Text('제목을 입력하세요')),
                );
                return;
              }

              final result = await AdminApiService.addVolume(
                seriesId: widget.seriesId,
                volumeNumber: volumeNumber,
                volumeTitle: title,
                price: price,
              );

              if (result['success'] == true) {
                Navigator.pop(context, true);
              } else {
                ScaffoldMessenger.of(context).showSnackBar(
                  SnackBar(content: Text('추가 실패: ${result['message']}')),
                );
              }
            },
            child: Text('추가'),
          ),
        ],
      ),
    );

    if (result == true) {
      _loadVolumes();
    }
  }

  Future<void> _showEditVolumeDialog(Map<String, dynamic> volume) async {
    final titleController = TextEditingController(text: volume['title']);
    final volumeNumberController = TextEditingController(
      text: volume['volume_number'].toString(),
    );
    final priceController = TextEditingController(
      text: volume['price'].toString(),
    );

    final result = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        title: Text('권 수정'),
        content: SingleChildScrollView(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              TextField(
                controller: titleController,
                decoration: InputDecoration(labelText: '제목'),
              ),
              TextField(
                controller: volumeNumberController,
                decoration: InputDecoration(labelText: '권 번호'),
                keyboardType: TextInputType.number,
              ),
              TextField(
                controller: priceController,
                decoration: InputDecoration(labelText: '가격'),
                keyboardType: TextInputType.number,
              ),
            ],
          ),
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: Text('취소'),
          ),
          ElevatedButton(
            onPressed: () async {
              final title = titleController.text.trim();
              final volumeNumber = int.tryParse(volumeNumberController.text) ?? 0;
              final price = int.tryParse(priceController.text) ?? 0;

              final result = await AdminApiService.updateVolume(
                volumeId: volume['volume_id'],
                volumeNumber: volumeNumber,
                volumeTitle: title,
                price: price,
              );

              if (result['success'] == true) {
                Navigator.pop(context, true);
              } else {
                ScaffoldMessenger.of(context).showSnackBar(
                  SnackBar(content: Text('수정 실패: ${result['message']}')),
                );
              }
            },
            child: Text('수정'),
          ),
        ],
      ),
    );

    if (result == true) {
      _loadVolumes();
    }
  }

  Future<void> _deleteVolume(int volumeId) async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        title: Text('권 삭제'),
        content: Text('정말 삭제하시겠습니까?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: Text('취소'),
          ),
          ElevatedButton(
            onPressed: () => Navigator.pop(context, true),
            style: ElevatedButton.styleFrom(backgroundColor: Colors.red),
            child: Text('삭제'),
          ),
        ],
      ),
    );

    if (confirm == true) {
      final result = await AdminApiService.deleteVolume(volumeId: volumeId);
      if (result['success'] == true) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('삭제 완료')),
        );
        _loadVolumes();
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('삭제 실패: ${result['message']}')),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('${widget.seriesTitle} - 권 관리'),
        actions: [
          IconButton(
            icon: Icon(Icons.add),
            onPressed: _showAddVolumeDialog,
          ),
        ],
      ),
      body: isLoading
          ? Center(child: CircularProgressIndicator())
          : volumes.isEmpty
          ? Center(child: Text('권이 없습니다'))
          : ListView.builder(
        padding: EdgeInsets.all(16),
        itemCount: volumes.length,
        itemBuilder: (context, index) {
          final volume = volumes[index];
          final price = volume['price'] ?? 0;
          final discount = volume['discount_rate'] ?? 0;
          final finalPrice = price - (price * discount / 100).round();

          return Card(
            margin: EdgeInsets.only(bottom: 16),
            child: Padding(
              padding: EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              '${volume['volume_number']}권: ${volume['title']}',
                              style: TextStyle(
                                fontSize: 16,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                            SizedBox(height: 8),
                            Text(
                              '가격: $price원 → $finalPrice원 ($discount% 할인)',
                            ),
                            Text('페이지: ${volume['page_count']}페이지'),
                          ],
                        ),
                      ),
                      Column(
                        children: [
                          IconButton(
                            icon: Icon(Icons.edit, color: Colors.blue),
                            onPressed: () => _showEditVolumeDialog(volume),
                          ),
                          IconButton(
                            icon: Icon(Icons.delete, color: Colors.red),
                            onPressed: () => _deleteVolume(volume['volume_id']),
                          ),
                        ],
                      ),
                    ],
                  ),
                  Divider(),
                  // 업로드 버튼들
                  Row(
                    children: [
                      Expanded(
                        child: ElevatedButton.icon(
                          onPressed: () => _uploadCover(volume['volume_id']),
                          icon: Icon(Icons.image),
                          label: Text('커버 업로드'),
                          style: ElevatedButton.styleFrom(
                            backgroundColor: Colors.green,
                          ),
                        ),
                      ),
                      SizedBox(width: 8),
                      Expanded(
                        child: ElevatedButton.icon(
                          onPressed: () => _uploadPages(volume['volume_id']),
                          icon: Icon(Icons.folder_zip),
                          label: Text('페이지 업로드'),
                          style: ElevatedButton.styleFrom(
                            backgroundColor: Colors.orange,
                          ),
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          );
        },
      ),
    );
  }
}