
import 'package:flutter/material.dart';

import 'dashboard_page.dart';

class MainNavigationPage extends StatefulWidget {
  const MainNavigationPage({super.key});

  @override
  State<MainNavigationPage> createState() => _MainNavigationPageState();
}

class _MainNavigationPageState extends State<MainNavigationPage> {
  int selectedIndex = 0;

  final List<NavigationItem> menuItems = [
    NavigationItem('Dashboard', Icons.dashboard_outlined),
    NavigationItem('Kasir', Icons.point_of_sale_outlined),
    NavigationItem('Produk', Icons.inventory_2_outlined),
    NavigationItem('Inventori', Icons.warehouse_outlined),
    NavigationItem('Pembelian', Icons.shopping_cart_outlined),
    NavigationItem('Karyawan', Icons.people_outline),
    NavigationItem('Absensi', Icons.access_time_outlined),
    NavigationItem('Laporan', Icons.bar_chart_outlined),
    NavigationItem('Pengaturan', Icons.settings_outlined),
  ];

  @override
  Widget build(BuildContext context) {
    final isWide = MediaQuery.of(context).size.width >= 700;

    return Scaffold(
      backgroundColor: const Color(0xFFF5F7FB),
      body: Row(
        children: [
          if (isWide) _buildSidebar(),

          Expanded(
            child: Column(
              children: [
                _buildHeader(),
                Expanded(child: _buildPage()),
              ],
            ),
          ),
        ],
      ),
      bottomNavigationBar: isWide ? null : _buildBottomNavigation(),
    );
  }

  Widget _buildHeader() {
    return Container(
      height: 65,
      padding: const EdgeInsets.symmetric(horizontal: 20),
      color: Colors.white,
      child: Row(
        children: [
          const Text(
            'Bless POS',
            style: TextStyle(
              fontSize: 20,
              fontWeight: FontWeight.bold,
              color: Color(0xFF2563EB),
            ),
          ),
          const Spacer(),
          IconButton(
            onPressed: () {},
            icon: const Icon(Icons.notifications_none),
          ),
          const CircleAvatar(
            radius: 17,
            backgroundColor: Color(0xFF2563EB),
            child: Icon(Icons.person, color: Colors.white, size: 19),
          ),
        ],
      ),
    );
  }

  Widget _buildSidebar() {
    return Container(
      width: 230,
      color: Colors.white,
      child: Column(
        children: [
          const SizedBox(height: 25),
          const Text(
            'MENU UTAMA',
            style: TextStyle(
              color: Color(0xFF94A3B8),
              fontSize: 11,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 15),
          Expanded(
            child: ListView.builder(
              itemCount: menuItems.length,
              itemBuilder: (context, index) {
                return _buildMenuItem(index);
              },
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildMenuItem(int index) {
    final item = menuItems[index];
    final selected = selectedIndex == index;

    return ListTile(
      selected: selected,
      selectedTileColor: const Color(0xFFEFF6FF),
      leading: Icon(
        item.icon,
        color: selected
            ? const Color(0xFF2563EB)
            : const Color(0xFF64748B),
      ),
      title: Text(
        item.title,
        style: TextStyle(
          fontSize: 13,
          fontWeight: selected ? FontWeight.bold : FontWeight.normal,
          color: selected
              ? const Color(0xFF2563EB)
              : const Color(0xFF475569),
        ),
      ),
      onTap: () {
        setState(() => selectedIndex = index);
      },
    );
  }

  Widget _buildBottomNavigation() {
    return NavigationBar(
      selectedIndex: selectedIndex,
      onDestinationSelected: (index) {
        setState(() => selectedIndex = index);
      },
      destinations: menuItems.map((item) {
        return NavigationDestination(
          icon: Icon(item.icon),
          label: item.title,
        );
      }).toList(),
    );
  }

  Widget _buildPage() {
    if (selectedIndex == 0) {
      return const DashboardPage();
    }

    return Center(
      child: Text(
        menuItems[selectedIndex].title,
        style: const TextStyle(
          fontSize: 24,
          fontWeight: FontWeight.bold,
        ),
      ),
    );
  }
}

class NavigationItem {
  final String title;
  final IconData icon;

  NavigationItem(this.title, this.icon);
}
