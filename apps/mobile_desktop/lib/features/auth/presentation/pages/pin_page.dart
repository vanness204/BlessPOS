import 'package:flutter/material.dart';
import 'package:mobile_desktop/features/pos/presentation/pages/pos_page.dart';

const Color pinNavy = Color(0xFF142D4E);
const Color pinTeal = Color(0xFF0D9488);
const Color pinBg = Color(0xFFF4F7FA);

class PinPage extends StatefulWidget {
  final Map<String, String> outlet;
  final Map<String, String> employee;

  const PinPage({
    super.key,
    required this.outlet,
    required this.employee,
  });

  @override
  State<PinPage> createState() => _PinPageState();
}

class _PinPageState extends State<PinPage> {
  String pin = '';

  void addDigit(String digit) {
    if (pin.length >= 6) return;

    setState(() {
      pin += digit;
    });
  }

  void removeDigit() {
    if (pin.isEmpty) return;

    setState(() {
      pin = pin.substring(0, pin.length - 1);
    });
  }

  void verifyPin() {
    if (pin.length != 6) return;

    Navigator.pushAndRemoveUntil(
      context,
      MaterialPageRoute(
        builder: (context) => PosPage(
          outlet: widget.outlet,
          employee: widget.employee,
        ),
      ),
      (route) => false,
    );
  }

  Widget buildPinKey(String value) {
    if (value.isEmpty) return const SizedBox.shrink();

    final isBackspace = value == 'backspace';

    return InkWell(
      onTap: isBackspace ? removeDigit : () => addDigit(value),
      borderRadius: BorderRadius.circular(50),
      child: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          shape: BoxShape.circle,
          border: Border.all(color: Colors.grey.shade200),
        ),
        child: Center(
          child: isBackspace
              ? const Icon(Icons.backspace_outlined, color: pinNavy)
              : Text(
                  value,
                  style: const TextStyle(
                    fontSize: 25,
                    fontWeight: FontWeight.w600,
                    color: pinNavy,
                  ),
                ),
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final keys = [
      '1', '2', '3',
      '4', '5', '6',
      '7', '8', '9',
      '', '0', 'backspace',
    ];

    return Scaffold(
      backgroundColor: pinBg,
      appBar: AppBar(
        backgroundColor: pinBg,
        foregroundColor: pinNavy,
        elevation: 0,
        title: const Text(
          'PIN Karyawan',
          style: TextStyle(fontWeight: FontWeight.bold),
        ),
      ),
      body: SafeArea(
        child: Center(
          child: ConstrainedBox(
            constraints: const BoxConstraints(maxWidth: 420),
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 28),
              child: Column(
                children: [
                  const SizedBox(height: 20),
                  Container(
                    width: 76,
                    height: 76,
                    decoration: BoxDecoration(
                      color: const Color(0xFFE6F4F2),
                      borderRadius: BorderRadius.circular(22),
                    ),
                    child: const Icon(
                      Icons.person_rounded,
                      color: pinTeal,
                      size: 42,
                    ),
                  ),
                  const SizedBox(height: 18),
                  Text(
                    widget.employee['name'] ?? '',
                    style: const TextStyle(
                      fontSize: 22,
                      fontWeight: FontWeight.bold,
                      color: pinNavy,
                    ),
                  ),
                  const SizedBox(height: 5),
                  Text(
                    '${widget.employee['position']} • ${widget.outlet['name']}',
                    textAlign: TextAlign.center,
                    style: const TextStyle(
                      color: Colors.black54,
                      fontSize: 13,
                    ),
                  ),
                  const SizedBox(height: 32),
                  const Text(
                    'Masukkan PIN 6 Digit',
                    style: TextStyle(
                      color: pinNavy,
                      fontSize: 16,
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                  const SizedBox(height: 22),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: List.generate(6, (index) {
                      final filled = index < pin.length;

                      return Container(
                        width: 17,
                        height: 17,
                        margin: const EdgeInsets.symmetric(horizontal: 10),
                        decoration: BoxDecoration(
                          shape: BoxShape.circle,
                          color: filled ? pinTeal : Colors.transparent,
                          border: Border.all(
                            color: filled ? pinTeal : Colors.grey.shade400,
                            width: 1.5,
                          ),
                        ),
                      );
                    }),
                  ),
                  const SizedBox(height: 28),
                  Expanded(
                    child: GridView.builder(
                      itemCount: keys.length,
                      physics: const NeverScrollableScrollPhysics(),
                      gridDelegate:
                          const SliverGridDelegateWithFixedCrossAxisCount(
                        crossAxisCount: 3,
                        mainAxisSpacing: 12,
                        crossAxisSpacing: 22,
                        childAspectRatio: 1.45,
                      ),
                      itemBuilder: (context, index) {
                        return buildPinKey(keys[index]);
                      },
                    ),
                  ),
                  const SizedBox(height: 12),
                  SizedBox(
                    width: double.infinity,
                    height: 52,
                    child: ElevatedButton(
                      onPressed: pin.length == 6 ? verifyPin : null,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: pinTeal,
                        foregroundColor: Colors.white,
                        disabledBackgroundColor: Colors.grey.shade300,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(12),
                        ),
                      ),
                      child: const Text(
                        'Konfirmasi PIN',
                        style: TextStyle(
                          fontSize: 15,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ),
                  const SizedBox(height: 20),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}

