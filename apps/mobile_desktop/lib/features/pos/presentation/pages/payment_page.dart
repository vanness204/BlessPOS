import 'package:flutter/material.dart';

class PaymentPage extends StatefulWidget {
  final Map<String, String> outlet;
  final Map<String, String> employee;
  final List<Map<String, dynamic>> items;
  final int total;

  const PaymentPage({
    super.key,
    required this.outlet,
    required this.employee,
    required this.items,
    required this.total,
  });

  @override
  State<PaymentPage> createState() => _PaymentPageState();
}

class _PaymentPageState extends State<PaymentPage> {
  String paymentMethod = 'Tunai';
  final TextEditingController cashController = TextEditingController();
  bool paymentCompleted = false;

  int get cashReceived {
    final digits = cashController.text.replaceAll(RegExp(r'[^0-9]'), '');
    return int.tryParse(digits) ?? 0;
  }

  int get change => cashReceived - widget.total;

  String formatRupiah(int value) {
    final digits = value.toString();
    final result = StringBuffer();

    for (int i = 0; i < digits.length; i++) {
      result.write(digits[i]);
      final remaining = digits.length - i - 1;
      if (remaining > 0 && remaining % 3 == 0) {
        result.write('.');
      }
    }

    return 'Rp ${result.toString()}';
  }

  void confirmPayment() {
    if (paymentMethod == 'Tunai' && cashReceived < widget.total) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Uang yang diterima masih kurang.')),
      );
      return;
    }

    setState(() {
      paymentCompleted = true;
    });
  }

  @override
  void dispose() {
    cashController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF4F7FA),
      appBar: AppBar(
        title: Text(paymentCompleted ? 'Transaksi Berhasil' : 'Pembayaran'),
        backgroundColor: Colors.white,
        foregroundColor: const Color(0xFF142D4E),
        elevation: 0,
        automaticallyImplyLeading: !paymentCompleted,
      ),
      body: Center(
        child: ConstrainedBox(
          constraints: const BoxConstraints(maxWidth: 650),
          child: ListView(
            padding: const EdgeInsets.all(20),
            children: [
              if (paymentCompleted) ...[
                const Icon(
                  Icons.check_circle,
                  color: Color(0xFF0D9488),
                  size: 76,
                ),
                const SizedBox(height: 12),
                const Text(
                  'Pembayaran Berhasil!',
                  textAlign: TextAlign.center,
                  style: TextStyle(fontSize: 23, fontWeight: FontWeight.bold),
                ),
                const SizedBox(height: 20),
              ],
              _section(
                title: paymentCompleted ? 'Struk Transaksi' : 'Ringkasan Pesanan',
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text('Outlet: ${widget.outlet['name'] ?? '-'}'),
                    Text('Kasir: ${widget.employee['name'] ?? '-'}'),
                    const Divider(height: 24),
                    ...widget.items.map((item) {
                      final name = item['name'] as String;
                      final quantity = item['quantity'] as int;
                      final price = item['price'] as int;

                      return Padding(
                        padding: const EdgeInsets.symmetric(vertical: 6),
                        child: Row(
                          children: [
                            Expanded(
                              child: Text('$name x$quantity'),
                            ),
                            Text(formatRupiah(price * quantity)),
                          ],
                        ),
                      );
                    }),
                    const Divider(height: 24),
                    _amountRow('Total Pembayaran', widget.total, bold: true),
                    if (paymentCompleted) ...[
                      const SizedBox(height: 12),
                      _amountRow(
                        'Metode Pembayaran',
                        0,
                        customValue: paymentMethod,
                      ),
                      if (paymentMethod == 'Tunai') ...[
                        _amountRow('Uang Diterima', cashReceived),
                        _amountRow('Kembalian', change, bold: true),
                      ],
                    ],
                  ],
                ),
              ),
              if (!paymentCompleted) ...[
                const SizedBox(height: 20),
                _section(
                  title: 'Metode Pembayaran',
                  child: Column(
                    children: [
                      _methodTile('Tunai', Icons.payments),
                      const SizedBox(height: 8),
                      _methodTile('Transfer / QRIS Manual', Icons.qr_code),
                    ],
                  ),
                ),
                if (paymentMethod == 'Tunai') ...[
                  const SizedBox(height: 20),
                  _section(
                    title: 'Uang Diterima',
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        TextField(
                          controller: cashController,
                          keyboardType: TextInputType.number,
                          onChanged: (_) => setState(() {}),
                          decoration: const InputDecoration(
                            prefixText: 'Rp ',
                            hintText: 'Masukkan nominal uang',
                            border: OutlineInputBorder(),
                          ),
                        ),
                        const SizedBox(height: 12),
                        Text(
                          'Kembalian: ${formatRupiah(change < 0 ? 0 : change)}',
                          style: const TextStyle(
                            fontSize: 17,
                            fontWeight: FontWeight.bold,
                            color: Color(0xFF0D9488),
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
                const SizedBox(height: 24),
                SizedBox(
                  height: 52,
                  child: ElevatedButton(
                    onPressed: confirmPayment,
                    style: ElevatedButton.styleFrom(
                      backgroundColor: const Color(0xFF0D9488),
                      foregroundColor: Colors.white,
                    ),
                    child: const Text(
                      'Konfirmasi Pembayaran',
                      style: TextStyle(fontWeight: FontWeight.bold),
                    ),
                  ),
                ),
              ] else ...[
                const SizedBox(height: 24),
                SizedBox(
                  height: 52,
                  child: ElevatedButton(
                    onPressed: () => Navigator.pop(context, true),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: const Color(0xFF0D9488),
                      foregroundColor: Colors.white,
                    ),
                    child: const Text('Selesai'),
                  ),
                ),
              ],
            ],
          ),
        ),
      ),
    );
  }

  Widget _section({required String title, required Widget child}) {
    return Container(
      padding: const EdgeInsets.all(18),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(14),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            title,
            style: const TextStyle(fontSize: 17, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 16),
          child,
        ],
      ),
    );
  }

  Widget _methodTile(String method, IconData icon) {
    final selected = paymentMethod == method;

    return InkWell(
      onTap: () {
        setState(() {
          paymentMethod = method;
        });
      },
      borderRadius: BorderRadius.circular(10),
      child: Container(
        padding: const EdgeInsets.all(14),
        decoration: BoxDecoration(
          border: Border.all(
            color: selected ? const Color(0xFF0D9488) : Colors.grey.shade300,
            width: selected ? 2 : 1,
          ),
          borderRadius: BorderRadius.circular(10),
        ),
        child: Row(
          children: [
            Icon(icon, color: const Color(0xFF0D9488)),
            const SizedBox(width: 12),
            Expanded(child: Text(method)),
            if (selected)
              const Icon(Icons.check_circle, color: Color(0xFF0D9488)),
          ],
        ),
      ),
    );
  }

  Widget _amountRow(
    String label,
    int amount, {
    bool bold = false,
    String? customValue,
  }) {
    final style = TextStyle(
      fontWeight: bold ? FontWeight.bold : FontWeight.normal,
      fontSize: bold ? 17 : 14,
    );

    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 5),
      child: Row(
        children: [
          Expanded(child: Text(label, style: style)),
          Text(customValue ?? formatRupiah(amount), style: style),
        ],
      ),
    );
  }
}
