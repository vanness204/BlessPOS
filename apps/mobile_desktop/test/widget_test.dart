
import 'package:flutter_test/flutter_test.dart';
import 'package:mobile_desktop/main.dart';

void main() {
  testWidgets('Bless POS menampilkan halaman login', (
      WidgetTester tester,
      ) async {
    await tester.pumpWidget(const BlessPosApp());

    expect(find.text('Bless POS'), findsOneWidget);
    expect(find.text('Selamat Datang!'), findsOneWidget);
    expect(find.text('Email'), findsOneWidget);
    expect(find.text('Password'), findsOneWidget);
    expect(find.text('Masuk'), findsOneWidget);
  });
}
