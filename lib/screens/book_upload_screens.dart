import 'package:flutter/material.dart';
import 'package:file_picker/file_picker.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

// ========================================
// 시리즈 관리 화면 (완전판)
// ========================================

class SeriesManageScreen extends StatefulWidget {
  const SeriesManageScreen({Key? key}) : super(key: key);

  @override
  State<SeriesManageScreen> createState() => _SeriesManageScreenState();
}

class _SeriesManageScreenState extends State<SeriesManageScreen> {
  List<Map<String, dynamic>> _seriesList = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadSeries();
  }

  Future<void> _loadSeries() async {
    setState(() => _isLoading = true);

    // TODO: API 호출
    // 임시 데이터
    await Future.delayed(const Duration(seconds: 1));

    setState(() {
      _seriesList = [
        {
          'series_id': 1,
          'series_name': '스파이더맨',
          'series_name_en': 'Spider-Man',
          'author': 'Stan Lee',
          'publisher_name': 'Marvel Comics',
          'total_volumes': 2,
          'status': 'ongoing'
        },
        {
          'series_id': 2,
          'series_name': '아이언맨',
          'series_name_en': 'Iron Man',
          'author': 'Stan Lee',
          'publisher_name': 'Marvel Comics',
          'total_volumes': 0,
          'status': 'ongoing'
        },
      ];
      _isLoading = false;
    });
  }

  void _showAddSeriesDialog() {
    showDialog(
      context: context,
      builder: (context) => const AddSeriesDialog(),
    ).then((result) {
      if (result == true) {
        _loadSeries();
      }
    });
  }

  void _openVolumeManage(Map<String, dynamic> series) {
    Navigator.push(
      context,
      MaterialPageRoute(
        builder: (context) => VolumeManageScreen(series: series),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : Column(
        children: [
          // 헤더
          Container(
            padding: const EdgeInsets.all(20),
            child: Row(
              children: [
                const Text(
                  '시리즈 관리',
                  style: TextStyle(
                    fontSize: 24,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const Spacer(),
                ElevatedButton.icon(
                  onPressed: _showAddSeriesDialog,
                  icon: const Icon(Icons.add),
                  label: const Text('시리즈 추가'),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.red.shade700,
                    foregroundColor: Colors.white,
                  ),
                ),
              ],
            ),
          ),

          // 시리즈 목록
          Expanded(
            child: _seriesList.isEmpty
                ? const Center(child: Text('등록된 시리즈가 없습니다'))
                : GridView.builder(
              padding: const EdgeInsets.all(20),
              gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                crossAxisCount: 4,
                crossAxisSpacing: 20,
                mainAxisSpacing: 20,
                childAspectRatio: 0.7,
              ),
              itemCount: _seriesList.length,
              itemBuilder: (context, index) {
                final series = _seriesList[index];
                return _buildSeriesCard(series);
              },
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildSeriesCard(Map<String, dynamic> series) {
    return Card(
      elevation: 2,
      child: InkWell(
        onTap: () => _openVolumeManage(series),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // 표지 이미지
            Expanded(
              child: Container(
                width: double.infinity,
                decoration: BoxDecoration(
                  color: Colors.grey.shade300,
                  borderRadius: const BorderRadius.vertical(top: Radius.circular(4)),
                ),
                child: series['cover_image'] != null
                    ? Image.network(series['cover_image'], fit: BoxFit.cover)
                    : Icon(Icons.collections_bookmark, size: 64, color: Colors.grey.shade500),
              ),
            ),

            // 정보
            Padding(
              padding: const EdgeInsets.all(12),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    series['series_name'],
                    style: const TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.bold,
                    ),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                  ),
                  const SizedBox(height: 4),
                  Text(
                    series['author'] ?? '',
                    style: TextStyle(
                      fontSize: 12,
                      color: Colors.grey.shade600,
                    ),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                  ),
                  const SizedBox(height: 8),
                  Row(
                    children: [
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                        decoration: BoxDecoration(
                          color: Colors.blue.shade100,
                          borderRadius: BorderRadius.circular(12),
                        ),
                        child: Text(
                          '${series['total_volumes']}권',
                          style: TextStyle(
                            fontSize: 12,
                            color: Colors.blue.shade700,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ),
                      const SizedBox(width: 8),
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                        decoration: BoxDecoration(
                          color: series['status'] == 'ongoing'
                              ? Colors.green.shade100
                              : Colors.grey.shade200,
                          borderRadius: BorderRadius.circular(12),
                        ),
                        child: Text(
                          series['status'] == 'ongoing' ? '연재중' : '완결',
                          style: TextStyle(
                            fontSize: 12,
                            color: series['status'] == 'ongoing'
                                ? Colors.green.shade700
                                : Colors.grey.shade700,
                          ),
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

// 시리즈 추가 다이얼로그
class AddSeriesDialog extends StatefulWidget {
  const AddSeriesDialog({Key? key}) : super(key: key);

  @override
  State<AddSeriesDialog> createState() => _AddSeriesDialogState();
}

class _AddSeriesDialogState extends State<AddSeriesDialog> {
  final _formKey = GlobalKey<FormState>();
  final _nameController = TextEditingController();
  final _authorController = TextEditingController();
  String _category = 'MARVEL';

  @override
  void dispose() {
    _nameController.dispose();
    _authorController.dispose();
    super.dispose();
  }

  void _submit() {
    if (_formKey.currentState!.validate()) {
      // TODO: API 호출
      Navigator.pop(context, true);
    }
  }

  @override
  Widget build(BuildContext context) {
    return AlertDialog(
      title: const Text('시리즈 추가'),
      content: Form(
        key: _formKey,
        child: SingleChildScrollView(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              TextFormField(
                controller: _nameController,
                decoration: const InputDecoration(
                  labelText: '시리즈명',
                  hintText: '예: 스파이더맨',
                ),
                validator: (v) => v?.isEmpty ?? true ? '필수 입력' : null,
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: _authorController,
                decoration: const InputDecoration(
                  labelText: '작가',
                  hintText: '예: Stan Lee',
                ),
              ),
              const SizedBox(height: 16),
              DropdownButtonFormField<String>(
                value: _category,
                decoration: const InputDecoration(labelText: '카테고리'),
                items: const [
                  DropdownMenuItem(value: 'MARVEL', child: Text('Marvel')),
                  DropdownMenuItem(value: 'DC', child: Text('DC')),
                  DropdownMenuItem(value: 'IMAGE', child: Text('Image')),
                  DropdownMenuItem(value: 'JAPANESE', child: Text('Japanese')),
                  DropdownMenuItem(value: 'KOREAN', child: Text('Korean')),
                ],
                onChanged: (v) => setState(() => _category = v!),
              ),
            ],
          ),
        ),
      ),
      actions: [
        TextButton(
          onPressed: () => Navigator.pop(context),
          child: const Text('취소'),
        ),
        ElevatedButton(
          onPressed: _submit,
          child: const Text('추가'),
        ),
      ],
    );
  }
}

// ========================================
// 권 관리 화면
// ========================================

class VolumeManageScreen extends StatefulWidget {
  final Map<String, dynamic> series;

  const VolumeManageScreen({Key? key, required this.series}) : super(key: key);

  @override
  State<VolumeManageScreen> createState() => _VolumeManageScreenState();
}

class _VolumeManageScreenState extends State<VolumeManageScreen> {
  List<Map<String, dynamic>> _volumes = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadVolumes();
  }

  Future<void> _loadVolumes() async {
    setState(() => _isLoading = true);

    // TODO: API 호출
    await Future.delayed(const Duration(milliseconds: 500));

    setState(() {
      _volumes = [
        {
          'volume_id': 1,
          'volume_number': 1,
          'volume_title': 'Amazing Fantasy',
          'total_pages': 24,
          'price': 3990,
          'is_free': 1,
          'status': 'published'
        },
        {
          'volume_id': 2,
          'volume_number': 2,
          'volume_title': 'The Vulture',
          'total_pages': 0,
          'price': 3990,
          'is_free': 0,
          'status': 'draft'
        },
      ];
      _isLoading = false;
    });
  }

  void _showAddVolumeDialog() {
    showDialog(
      context: context,
      builder: (context) => AddVolumeDialog(seriesId: widget.series['series_id']),
    ).then((result) {
      if (result == true) {
        _loadVolumes();
      }
    });
  }

  void _openPageUpload(Map<String, dynamic> volume) {
    Navigator.push(
      context,
      MaterialPageRoute(
        builder: (context) => PageUploadScreen(
          series: widget.series,
          volume: volume,
        ),
      ),
    ).then((_) => _loadVolumes());
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('${widget.series['series_name']} - 권 관리'),
        backgroundColor: Colors.red.shade700,
        foregroundColor: Colors.white,
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : Column(
        children: [
          // 헤더
          Container(
            padding: const EdgeInsets.all(20),
            child: Row(
              children: [
                Text(
                  '총 ${_volumes.length}권',
                  style: const TextStyle(
                    fontSize: 20,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const Spacer(),
                ElevatedButton.icon(
                  onPressed: _showAddVolumeDialog,
                  icon: const Icon(Icons.add),
                  label: const Text('권 추가'),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.red.shade700,
                    foregroundColor: Colors.white,
                  ),
                ),
              ],
            ),
          ),

          // 권 목록
          Expanded(
            child: _volumes.isEmpty
                ? const Center(child: Text('등록된 권이 없습니다'))
                : ListView.builder(
              padding: const EdgeInsets.all(20),
              itemCount: _volumes.length,
              itemBuilder: (context, index) {
                final volume = _volumes[index];
                return _buildVolumeCard(volume);
              },
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildVolumeCard(Map<String, dynamic> volume) {
    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      child: ListTile(
        leading: CircleAvatar(
          backgroundColor: Colors.blue.shade100,
          child: Text(
            '${volume['volume_number']}',
            style: TextStyle(
              color: Colors.blue.shade700,
              fontWeight: FontWeight.bold,
            ),
          ),
        ),
        title: Text(
          '${volume['volume_number']}권 - ${volume['volume_title'] ?? '제목 없음'}',
          style: const TextStyle(fontWeight: FontWeight.bold),
        ),
        subtitle: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const SizedBox(height: 4),
            Row(
              children: [
                Icon(Icons.image, size: 16, color: Colors.grey.shade600),
                const SizedBox(width: 4),
                Text('${volume['total_pages']}페이지'),
                const SizedBox(width: 16),
                Icon(Icons.attach_money, size: 16, color: Colors.grey.shade600),
                const SizedBox(width: 4),
                Text('${volume['price']}원'),
                if (volume['is_free'] == 1) ...[
                  const SizedBox(width: 8),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                    decoration: BoxDecoration(
                      color: Colors.green.shade100,
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: Text(
                      '무료',
                      style: TextStyle(
                        fontSize: 11,
                        color: Colors.green.shade700,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                ],
              ],
            ),
          ],
        ),
        trailing: ElevatedButton.icon(
          onPressed: () => _openPageUpload(volume),
          icon: const Icon(Icons.upload_file, size: 18),
          label: Text(volume['total_pages'] > 0 ? '페이지 추가' : '페이지 업로드'),
          style: ElevatedButton.styleFrom(
            backgroundColor: Colors.orange.shade700,
            foregroundColor: Colors.white,
          ),
        ),
      ),
    );
  }
}

// 권 추가 다이얼로그
class AddVolumeDialog extends StatefulWidget {
  final int seriesId;

  const AddVolumeDialog({Key? key, required this.seriesId}) : super(key: key);

  @override
  State<AddVolumeDialog> createState() => _AddVolumeDialogState();
}

class _AddVolumeDialogState extends State<AddVolumeDialog> {
  final _formKey = GlobalKey<FormState>();
  final _numberController = TextEditingController();
  final _titleController = TextEditingController();
  final _priceController = TextEditingController(text: '3990');
  bool _isFree = false;

  @override
  void dispose() {
    _numberController.dispose();
    _titleController.dispose();
    _priceController.dispose();
    super.dispose();
  }

  void _submit() {
    if (_formKey.currentState!.validate()) {
      // TODO: API 호출
      Navigator.pop(context, true);
    }
  }

  @override
  Widget build(BuildContext context) {
    return AlertDialog(
      title: const Text('권 추가'),
      content: Form(
        key: _formKey,
        child: SingleChildScrollView(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              TextFormField(
                controller: _numberController,
                decoration: const InputDecoration(
                  labelText: '권 번호',
                  hintText: '예: 1',
                ),
                keyboardType: TextInputType.number,
                validator: (v) => v?.isEmpty ?? true ? '필수 입력' : null,
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: _titleController,
                decoration: const InputDecoration(
                  labelText: '권 제목',
                  hintText: '예: Amazing Fantasy',
                ),
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: _priceController,
                decoration: const InputDecoration(
                  labelText: '가격',
                  suffixText: '원',
                ),
                keyboardType: TextInputType.number,
              ),
              const SizedBox(height: 8),
              CheckboxListTile(
                title: const Text('무료로 제공'),
                value: _isFree,
                onChanged: (v) => setState(() => _isFree = v ?? false),
                controlAffinity: ListTileControlAffinity.leading,
                contentPadding: EdgeInsets.zero,
              ),
            ],
          ),
        ),
      ),
      actions: [
        TextButton(
          onPressed: () => Navigator.pop(context),
          child: const Text('취소'),
        ),
        ElevatedButton(
          onPressed: _submit,
          child: const Text('추가'),
        ),
      ],
    );
  }
}

// ========================================
// 페이지 업로드 화면 (핵심!)
// ========================================

class PageUploadScreen extends StatefulWidget {
  final Map<String, dynamic> series;
  final Map<String, dynamic> volume;

  const PageUploadScreen({
    Key? key,
    required this.series,
    required this.volume,
  }) : super(key: key);

  @override
  State<PageUploadScreen> createState() => _PageUploadScreenState();
}

class _PageUploadScreenState extends State<PageUploadScreen> {
  List<PlatformFile> _selectedFiles = [];
  bool _isUploading = false;
  double _uploadProgress = 0.0;
  String _statusMessage = '';

  Future<void> _pickFiles() async {
    try {
      FilePickerResult? result = await FilePicker.platform.pickFiles(
        type: FileType.image,
        allowMultiple: true,
      );

      if (result != null) {
        setState(() {
          _selectedFiles = result.files;
          _statusMessage = '${_selectedFiles.length}개 파일 선택됨';
        });
      }
    } catch (e) {
      _showError('파일 선택 오류: $e');
    }
  }

  Future<void> _uploadFiles() async {
    if (_selectedFiles.isEmpty) {
      _showError('업로드할 파일을 선택해주세요');
      return;
    }

    setState(() {
      _isUploading = true;
      _uploadProgress = 0.0;
      _statusMessage = '업로드 중...';
    });

    try {
      final uri = Uri.parse('http://34.64.84.117:8081/admin/apis/pages/upload.php');
      final request = http.MultipartRequest('POST', uri);

      // volume_id 추가
      request.fields['volume_id'] = widget.volume['volume_id'].toString();

      // 파일들 추가
      for (var file in _selectedFiles) {
        if (file.bytes != null) {
          request.files.add(
            http.MultipartFile.fromBytes(
              'files[]',
              file.bytes!,
              filename: file.name,
            ),
          );
        }
      }

      // 업로드 실행
      final streamedResponse = await request.send();
      final response = await http.Response.fromStream(streamedResponse);

      if (response.statusCode == 200) {
        final data = json.decode(response.body);

        if (data['code'] == 0) {
          setState(() {
            _uploadProgress = 1.0;
            _statusMessage = '업로드 완료! ${data['data']['uploaded_count']}개 파일';
          });

          _showSuccess('${data['data']['uploaded_count']}개 파일이 업로드되었습니다!');

          // 2초 후 자동으로 돌아가기
          Future.delayed(const Duration(seconds: 2), () {
            if (mounted) {
              Navigator.pop(context);
            }
          });
        } else {
          _showError(data['msg'] ?? '업로드 실패');
        }
      } else {
        _showError('서버 오류: ${response.statusCode}');
      }

    } catch (e) {
      _showError('업로드 오류: $e');
    } finally {
      setState(() {
        _isUploading = false;
      });
    }
  }

  void _showError(String message) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text(message), backgroundColor: Colors.red),
    );
    setState(() {
      _statusMessage = message;
    });
  }

  void _showSuccess(String message) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text(message), backgroundColor: Colors.green),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('페이지 업로드 - ${widget.volume['volume_number']}권'),
        backgroundColor: Colors.orange.shade700,
        foregroundColor: Colors.white,
      ),
      body: Padding(
        padding: const EdgeInsets.all(20),
        child: Column(
          children: [
            // 정보 카드
            Card(
              color: Colors.blue.shade50,
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      widget.series['series_name'],
                      style: const TextStyle(
                        fontSize: 20,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    const SizedBox(height: 4),
                    Text('${widget.volume['volume_number']}권 - ${widget.volume['volume_title']}'),
                    const SizedBox(height: 8),
                    Text(
                      '현재 페이지: ${widget.volume['total_pages']}장',
                      style: TextStyle(color: Colors.grey.shade700),
                    ),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 20),

            // 파일 선택 영역
            Expanded(
              child: Container(
                decoration: BoxDecoration(
                  border: Border.all(color: Colors.grey.shade300, width: 2),
                  borderRadius: BorderRadius.circular(8),
                  color: Colors.grey.shade50,
                ),
                child: _selectedFiles.isEmpty
                    ? Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Icon(Icons.cloud_upload, size: 64, color: Colors.grey.shade400),
                      const SizedBox(height: 16),
                      const Text('이미지 파일을 선택해주세요'),
                      const SizedBox(height: 8),
                      Text(
                        'JPG, PNG, GIF, WebP 지원',
                        style: TextStyle(color: Colors.grey.shade600, fontSize: 12),
                      ),
                    ],
                  ),
                )
                    : ListView.builder(
                  padding: const EdgeInsets.all(8),
                  itemCount: _selectedFiles.length,
                  itemBuilder: (context, index) {
                    final file = _selectedFiles[index];
                    return Card(
                      child: ListTile(
                        leading: const Icon(Icons.image),
                        title: Text(file.name),
                        subtitle: Text('${(file.size / 1024).toStringAsFixed(1)} KB'),
                        trailing: IconButton(
                          icon: const Icon(Icons.close),
                          onPressed: () {
                            setState(() {
                              _selectedFiles.removeAt(index);
                            });
                          },
                        ),
                      ),
                    );
                  },
                ),
              ),
            ),
            const SizedBox(height: 16),

            // 진행률 표시
            if (_isUploading) ...[
              LinearProgressIndicator(value: _uploadProgress),
              const SizedBox(height: 8),
            ],

            Text(
              _statusMessage,
              style: TextStyle(
                color: _statusMessage.contains('완료') ? Colors.green : Colors.grey.shade700,
              ),
            ),
            const SizedBox(height: 16),

            // 버튼들
            Row(
              children: [
                Expanded(
                  child: ElevatedButton.icon(
                    onPressed: _isUploading ? null : _pickFiles,
                    icon: const Icon(Icons.folder_open),
                    label: Text(_selectedFiles.isEmpty ? '파일 선택' : '파일 추가'),
                    style: ElevatedButton.styleFrom(
                      padding: const EdgeInsets.all(16),
                      backgroundColor: Colors.grey.shade700,
                      foregroundColor: Colors.white,
                    ),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: ElevatedButton.icon(
                    onPressed: _isUploading || _selectedFiles.isEmpty ? null : _uploadFiles,
                    icon: const Icon(Icons.cloud_upload),
                    label: const Text('업로드 시작'),
                    style: ElevatedButton.styleFrom(
                      padding: const EdgeInsets.all(16),
                      backgroundColor: Colors.orange.shade700,
                      foregroundColor: Colors.white,
                    ),
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}