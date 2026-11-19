import 'package:flutter/material.dart';
import 'payment_page.dart';

const Color posNavy = Color(0xFF142D4E);
const Color posTeal = Color(0xFF0D9488);
const Color posBg = Color(0xFFF4F7FA);

class PosProduct {
  final int id;
  final String name;
  final String category;
  final int price;
  final IconData icon;

  const PosProduct({
    required this.id,
    required this.name,
    required this.category,
    required this.price,
    required this.icon,
  });
}

class PosPage extends StatefulWidget {
  final Map<String, String> outlet;
  final Map<String, String> employee;

  const PosPage({
    super.key,
    required this.outlet,
    required this.employee,
  });

  @override
  State<PosPage> createState() => _PosPageState();
}

class _PosPageState extends State<PosPage> {
  String selectedCategory = 'Semua';

  final Map<int, int> cart = {};

  final List<PosProduct> products = const [
    PosProduct(id: 1, name: 'Nasi Goreng', category: 'Makanan', price: 25000, icon: Icons.rice_bowl),
    PosProduct(id: 2, name: 'Mie Goreng', category: 'Makanan', price: 22000, icon: Icons.ramen_dining),
    PosProduct(id: 3, name: 'Ayam Geprek', category: 'Makanan', price: 28000, icon: Icons.restaurant),
    PosProduct(id: 4, name: 'Nasi Ayam', category: 'Makanan', price: 30000, icon: Icons.lunch_dining),
    PosProduct(id: 5, name: 'Es Teh', category: 'Minuman', price: 8000, icon: Icons.local_cafe),
    PosProduct(id: 6, name: 'Kopi Susu', category: 'Minuman', price: 18000, icon: Icons.coffee),
    PosProduct(id: 7, name: 'Jus Jeruk', category: 'Minuman', price: 15000, icon: Icons.local_drink),
    PosProduct(id: 8, name: 'Air Mineral', category: 'Minuman', price: 5000, icon: Icons.water_drop),
    PosProduct(id: 9, name: 'Kentang Goreng', category: 'Snack', price: 16000, icon: Icons.fastfood),
    PosProduct(id: 10, name: 'Roti Bakar', category: 'Snack', price: 18000, icon: Icons.bakery_dining),
  ];

  final List<String> categories = [
    'Semua',
    'Makanan',
    'Minuman',
    'Snack',
  ];

  int get totalItems => cart.values.fold(0, (sum, qty) => sum + qty);

  int get subtotal {
    return products.fold(0, (sum, product) {
      return sum + product.price * (cart[product.id] ?? 0);
    });
  }

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

  void addProduct(PosProduct product) {
    setState(() {
      cart[product.id] = (cart[product.id] ?? 0) + 1;
    });
  }

  void decreaseProduct(PosProduct product) {
    setState(() {
      final quantity = cart[product.id] ?? 0;

      if (quantity <= 1) {
        cart.remove(product.id);
      } else {
        cart[product.id] = quantity - 1;
      }
    });
  }

  Future<void> checkout() async {
    if (cart.isEmpty) return;

    final items = products
        .where((product) => (cart[product.id] ?? 0) > 0)
        .map((product) => {
              'id': product.id,
              'name': product.name,
              'price': product.price,
              'quantity': cart[product.id]!,
            })
        .toList();

    final result = await Navigator.push<bool>(
      context,
      MaterialPageRoute(
        builder: (context) => PaymentPage(
          outlet: widget.outlet,
          employee: widget.employee,
          items: items,
          total: subtotal,
        ),
      ),
    );

    if (result == true && mounted) {
      setState(() {
        cart.clear();
      });

      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Transaksi berhasil diselesaikan.'),
        ),
      );
    }
  }

  Widget buildProductCard(PosProduct product) {
    final quantity = cart[product.id] ?? 0;

    return InkWell(
      onTap: () => addProduct(product),
      borderRadius: BorderRadius.circular(14),
      child: Container(
        padding: const EdgeInsets.all(14),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(14),
          border: Border.all(color: Colors.grey.shade200),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Expanded(
              child: Center(
                child: Container(
                  width: 62,
                  height: 62,
                  decoration: BoxDecoration(
                    color: const Color(0xFFE6F4F2),
                    borderRadius: BorderRadius.circular(16),
                  ),
                  child: Icon(
                    product.icon,
                    size: 32,
                    color: posTeal,
                  ),
                ),
              ),
            ),
            const SizedBox(height: 10),
            Text(
              product.name,
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
              style: const TextStyle(
                fontSize: 14,
                fontWeight: FontWeight.bold,
                color: posNavy,
              ),
            ),
            const SizedBox(height: 5),
            Text(
              formatRupiah(product.price),
              style: const TextStyle(
                fontSize: 13,
                color: posTeal,
                fontWeight: FontWeight.w600,
              ),
            ),
            const SizedBox(height: 8),
            Row(
              children: [
                const Expanded(
                  child: Text(
                    'Tambah produk',
                    style: TextStyle(fontSize: 10, color: Colors.black54),
                  ),
                ),
                if (quantity > 0)
                  Container(
                    padding: const EdgeInsets.symmetric(
                      horizontal: 8,
                      vertical: 3,
                    ),
                    decoration: BoxDecoration(
                      color: posTeal,
                      borderRadius: BorderRadius.circular(20),
                    ),
                    child: Text(
                      '$quantity',
                      style: const TextStyle(
                        color: Colors.white,
                        fontSize: 11,
                        fontWeight: FontWeight.bold,
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

  Widget buildCart() {
    final cartProducts = products
        .where((product) => (cart[product.id] ?? 0) > 0)
        .toList();

    return Container(
      width: 340,
      decoration: const BoxDecoration(
        color: Colors.white,
        border: Border(
          left: BorderSide(color: Color(0xFFE5E7EB)),
        ),
      ),
      child: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(20),
            child: Row(
              children: [
                const Icon(Icons.shopping_cart_outlined, color: posNavy),
                const SizedBox(width: 10),
                const Expanded(
                  child: Text(
                    'Keranjang Pesanan',
                    style: TextStyle(
                      fontSize: 17,
                      fontWeight: FontWeight.bold,
                      color: posNavy,
                    ),
                  ),
                ),
                Text(
                  '$totalItems item',
                  style: const TextStyle(color: Colors.black54),
                ),
              ],
            ),
          ),
          const Divider(height: 1),
          Expanded(
            child: cartProducts.isEmpty
                ? const Center(
                    child: Column(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Icon(
                          Icons.shopping_basket_outlined,
                          size: 55,
                          color: Colors.black26,
                        ),
                        SizedBox(height: 12),
                        Text(
                          'Keranjang masih kosong',
                          style: TextStyle(color: Colors.black54),
                        ),
                      ],
                    ),
                  )
                : ListView.separated(
                    padding: const EdgeInsets.all(16),
                    itemCount: cartProducts.length,
                    separatorBuilder: (_, __) =>
                        const SizedBox(height: 14),
                    itemBuilder: (context, index) {
                      final product = cartProducts[index];
                      final quantity = cart[product.id] ?? 0;

                      return Row(
                        children: [
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  product.name,
                                  style: const TextStyle(
                                    fontWeight: FontWeight.w600,
                                    color: posNavy,
                                  ),
                                ),
                                const SizedBox(height: 4),
                                Text(
                                  formatRupiah(product.price * quantity),
                                  style: const TextStyle(
                                    color: Colors.black54,
                                    fontSize: 12,
                                  ),
                                ),
                              ],
                            ),
                          ),
                          IconButton(
                            onPressed: () => decreaseProduct(product),
                            icon: const Icon(Icons.remove_circle_outline),
                            color: posTeal,
                          ),
                          Text(
                            '$quantity',
                            style: const TextStyle(
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                          IconButton(
                            onPressed: () => addProduct(product),
                            icon: const Icon(Icons.add_circle_outline),
                            color: posTeal,
                          ),
                        ],
                      );
                    },
                  ),
          ),
          const Divider(height: 1),
          Padding(
            padding: const EdgeInsets.all(20),
            child: Column(
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    const Text(
                      'Subtotal',
                      style: TextStyle(color: Colors.black54),
                    ),
                    Text(
                      formatRupiah(subtotal),
                      style: const TextStyle(
                        fontWeight: FontWeight.w600,
                        color: posNavy,
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 10),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    const Text(
                      'Total',
                      style: TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                        color: posNavy,
                      ),
                    ),
                    Text(
                      formatRupiah(subtotal),
                      style: const TextStyle(
                        fontSize: 19,
                        fontWeight: FontWeight.bold,
                        color: posTeal,
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 18),
                SizedBox(
                  width: double.infinity,
                  height: 50,
                  child: ElevatedButton(
                    onPressed: cart.isEmpty ? null : checkout,
                    style: ElevatedButton.styleFrom(
                      backgroundColor: posTeal,
                      foregroundColor: Colors.white,
                      disabledBackgroundColor: Colors.grey.shade300,
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(12),
                      ),
                    ),
                    child: const Text(
                      'Lanjut Pembayaran',
                      style: TextStyle(fontWeight: FontWeight.bold),
                    ),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  void showMobileCart() {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) {
        return SafeArea(
          child: SizedBox(
            height: MediaQuery.of(context).size.height * 0.8,
            child: ClipRRect(
              borderRadius: const BorderRadius.vertical(
                top: Radius.circular(22),
              ),
              child: buildCart(),
            ),
          ),
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: posBg,
      body: SafeArea(
        child: LayoutBuilder(
          builder: (context, constraints) {
            final isDesktop = constraints.maxWidth >= 850;

            final filteredProducts = selectedCategory == 'Semua'
                ? products
                : products
                    .where((p) => p.category == selectedCategory)
                    .toList();

            return Column(
              children: [
                Container(
                  color: Colors.white,
                  padding: const EdgeInsets.symmetric(
                    horizontal: 20,
                    vertical: 15,
                  ),
                  child: Row(
                    children: [
                      Container(
                        width: 42,
                        height: 42,
                        decoration: BoxDecoration(
                          color: posNavy,
                          borderRadius: BorderRadius.circular(12),
                        ),
                        child: const Icon(
                          Icons.point_of_sale_rounded,
                          color: Colors.white,
                        ),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              widget.outlet['name'] ?? 'Outlet',
                              style: const TextStyle(
                                color: posNavy,
                                fontWeight: FontWeight.bold,
                                fontSize: 15,
                              ),
                            ),
                            Text(
                              'Kasir: ${widget.employee['name'] ?? '-'}',
                              style: const TextStyle(
                                color: Colors.black54,
                                fontSize: 12,
                              ),
                            ),
                          ],
                        ),
                      ),
                      if (isDesktop)
                        const Text(
                          'BLESS POS',
                          style: TextStyle(
                            color: posTeal,
                            fontWeight: FontWeight.bold,
                            letterSpacing: 1,
                          ),
                        ),
                      if (!isDesktop)
                        IconButton(
                          onPressed: showMobileCart,
                          icon: const Icon(
                            Icons.shopping_cart_outlined,
                            color: posNavy,
                          ),
                        ),
                    ],
                  ),
                ),
                Expanded(
                  child: Row(
                    children: [
                      Expanded(
                        child: Padding(
                          padding: const EdgeInsets.all(18),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              const Text(
                                'Pilih Produk',
                                style: TextStyle(
                                  fontSize: 23,
                                  fontWeight: FontWeight.bold,
                                  color: posNavy,
                                ),
                              ),
                              const SizedBox(height: 5),
                              const Text(
                                'Pilih produk untuk ditambahkan ke pesanan.',
                                style: TextStyle(
                                  fontSize: 12,
                                  color: Colors.black54,
                                ),
                              ),
                              const SizedBox(height: 18),
                              SizedBox(
                                height: 40,
                                child: ListView.separated(
                                  scrollDirection: Axis.horizontal,
                                  itemCount: categories.length,
                                  separatorBuilder: (_, __) =>
                                      const SizedBox(width: 8),
                                  itemBuilder: (context, index) {
                                    final category = categories[index];
                                    final active =
                                        selectedCategory == category;

                                    return ChoiceChip(
                                      label: Text(category),
                                      selected: active,
                                      onSelected: (_) {
                                        setState(() {
                                          selectedCategory = category;
                                        });
                                      },
                                      selectedColor: posTeal,
                                      backgroundColor: Colors.white,
                                      labelStyle: TextStyle(
                                        color: active
                                            ? Colors.white
                                            : posNavy,
                                        fontWeight: FontWeight.w600,
                                      ),
                                      side: BorderSide(
                                        color: active
                                            ? posTeal
                                            : Colors.grey.shade300,
                                      ),
                                    );
                                  },
                                ),
                              ),
                              const SizedBox(height: 16),
                              Expanded(
                                child: GridView.builder(
                                  itemCount: filteredProducts.length,
                                  gridDelegate:
                                      SliverGridDelegateWithFixedCrossAxisCount(
                                    crossAxisCount: isDesktop ? 3 : 2,
                                    crossAxisSpacing: 12,
                                    mainAxisSpacing: 12,
                                    childAspectRatio: isDesktop ? 1.05 : 0.9,
                                  ),
                                  itemBuilder: (context, index) {
                                    return buildProductCard(
                                      filteredProducts[index],
                                    );
                                  },
                                ),
                              ),
                              if (!isDesktop)
                                SizedBox(
                                  width: double.infinity,
                                  height: 50,
                                  child: ElevatedButton.icon(
                                    onPressed: showMobileCart,
                                    icon: const Icon(
                                      Icons.shopping_cart_outlined,
                                    ),
                                    label: Text(
                                      'Keranjang ($totalItems) • ${formatRupiah(subtotal)}',
                                    ),
                                    style: ElevatedButton.styleFrom(
                                      backgroundColor: posTeal,
                                      foregroundColor: Colors.white,
                                    ),
                                  ),
                                ),
                            ],
                          ),
                        ),
                      ),
                      if (isDesktop) buildCart(),
                    ],
                  ),
                ),
              ],
            );
          },
        ),
      ),
    );
  }
}

