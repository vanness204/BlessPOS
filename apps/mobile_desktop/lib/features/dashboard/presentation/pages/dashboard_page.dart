
import 'package:flutter/material.dart';

class DashboardPage extends StatelessWidget {
  const DashboardPage({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF5F7FB),
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        title: const Text(
          'Bless POS',
          style: TextStyle(
            color: Color(0xFF1E293B),
            fontWeight: FontWeight.bold,
          ),
        ),
        actions: [
          IconButton(
            onPressed: () {},
            icon: const Icon(
              Icons.notifications_none,
              color: Color(0xFF475569),
            ),
          ),
          const Padding(
            padding: EdgeInsets.only(right: 16),
            child: CircleAvatar(
              backgroundColor: Color(0xFF2563EB),
              child: Icon(Icons.person, color: Colors.white),
            ),
          ),
        ],
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Dashboard',
              style: TextStyle(
                fontSize: 24,
                fontWeight: FontWeight.bold,
                color: Color(0xFF1E293B),
              ),
            ),
            const SizedBox(height: 6),
            const Text(
              'Selamat datang di Bless POS',
              style: TextStyle(color: Color(0xFF64748B)),
            ),
            const SizedBox(height: 24),

            _buildSummaryCard(
              title: 'Total Penjualan Hari Ini',
              value: 'Rp0',
              icon: Icons.point_of_sale,
              color: Color(0xFF2563EB),
            ),
            const SizedBox(height: 14),
            _buildSummaryCard(
              title: 'Total Transaksi',
              value: '0',
              icon: Icons.receipt_long,
              color: Color(0xFF16A34A),
            ),
            const SizedBox(height: 14),
            _buildSummaryCard(
              title: 'Produk Terdaftar',
              value: '0',
              icon: Icons.inventory_2,
              color: Color(0xFFF59E0B),
            ),
            const SizedBox(height: 14),
            _buildSummaryCard(
              title: 'Stok Menipis',
              value: '0',
              icon: Icons.warning_amber,
              color: Color(0xFFDC2626),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildSummaryCard({
    required String title,
    required String value,
    required IconData icon,
    required Color color,
  }) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
      ),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(
              color: color.withValues(alpha: 0.1),
              borderRadius: BorderRadius.circular(12),
            ),
            child: Icon(icon, color: color, size: 28),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  title,
                  style: const TextStyle(
                    color: Color(0xFF64748B),
                    fontSize: 13,
                  ),
                ),
                const SizedBox(height: 6),
                Text(
                  value,
                  style: const TextStyle(
                    color: Color(0xFF1E293B),
                    fontSize: 22,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
