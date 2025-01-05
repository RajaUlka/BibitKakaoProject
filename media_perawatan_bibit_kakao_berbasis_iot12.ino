#include <Wire.h>
#include <Adafruit_GFX.h>
#include <Adafruit_SSD1306.h>
#include <DHT.h>
#include <RTClib.h>

// Konfigurasi OLED
#define SCREEN_WIDTH 128
#define SCREEN_HEIGHT 64
Adafruit_SSD1306 display(SCREEN_WIDTH, SCREEN_HEIGHT, &Wire);

// Konfigurasi DHT11
#define DHTPIN 2
#define DHTTYPE DHT11
DHT dht(DHTPIN, DHTTYPE);

// Konfigurasi sensor kelembapan tanah
#define SOIL_MOISTURE_PIN A0

// Konfigurasi Relay (3 Channel)
#define RELAY_3 7  // Relay untuk pemupukan
#define RELAY_2 8  // Relay untuk penyiraman
#define RELAY_1 9  // Relay untuk lampu

// Variabel untuk pengaturan tampilan
unsigned long lastUpdateTime = 0;
int displayState = 0; // Menyimpan status tampilan

// Inisialisasi RTC
RTC_DS3231 rtc;

// Variabel pemupukan
bool fertilizing = false;
unsigned long fertilizingStartTime = 0; // Waktu mulai pemupukan
const unsigned long fertilizingDuration = 10000; // Durasi pemupukan dalam milidetik (10 detik)

void setup() {
  // Mulai Serial Monitor
  Serial.begin(9600);

  // Inisialisasi layar OLED
  if (!display.begin(SSD1306_SWITCHCAPVCC, 0x3C)) {
    Serial.println(F("OLED tidak terdeteksi! Periksa koneksi."));
    for (;;);
  }
  display.clearDisplay();

  // Inisialisasi DHT11
  dht.begin();

  // Inisialisasi RTC
  if (!rtc.begin()) {
    Serial.println(F("RTC tidak terdeteksi! Periksa koneksi."));
    display.setTextSize(1);
    display.setCursor(0, 0);
    display.println(F("RTC Error!"));
    display.display();
    for (;;);
  }

  // Atur waktu jika RTC belum diatur atau kehilangan daya
  if (rtc.lostPower()) {
    rtc.adjust(DateTime(2025, 1, 5, 14, 50, 0)); // Atur waktu sesuai kebutuhan (YYYY, MM, DD, HH, MM, SS)
    Serial.println(F("RTC diatur ulang ke waktu default."));
  }

  // Inisialisasi pin relay sebagai output
  pinMode(RELAY_1, OUTPUT);
  pinMode(RELAY_2, OUTPUT);
  pinMode(RELAY_3, OUTPUT);

  // Matikan relay pada awal program
  digitalWrite(RELAY_1, HIGH);
  digitalWrite(RELAY_2, HIGH);
  digitalWrite(RELAY_3, HIGH);

  // Menampilkan pesan awal pada OLED
  display.setTextColor(SSD1306_WHITE);
  display.setTextSize(1);
  display.setCursor(0, 0);
  display.println(F("Inisialisasi Sensor..."));
  display.display();
  delay(2000);
}

void loop() {
  // Membaca data dari DHT11
  float temperature = dht.readTemperature();
  float humidity = dht.readHumidity();

  // Membaca data kelembapan tanah
  int soilMoistureValue = analogRead(SOIL_MOISTURE_PIN);
  float soilMoisturePercent = map(soilMoistureValue, 1023, 0, 0, 100);

  // Membaca waktu dari RTC
  DateTime now = rtc.now();

  // Kontrol Relay untuk Pemupukan berdasarkan waktu
  if (!fertilizing && ((now.hour() == 7 && now.minute() == 0) || (now.hour() == 15 && now.minute() == 7))) {
    fertilizing = true;
    fertilizingStartTime = millis();
    digitalWrite(RELAY_3, LOW); // Nyalakan Relay Pemupukan
    Serial.println(F("Pemupukan dimulai."));
  }

  // Matikan Relay Pemupukan setelah durasi selesai
  if (fertilizing && millis() - fertilizingStartTime >= fertilizingDuration) {
    fertilizing = false;
    digitalWrite(RELAY_3, HIGH); // Matikan Relay Pemupukan
    Serial.println(F("Pemupukan selesai."));
  }

  // Kontrol Relay untuk Penyiraman berdasarkan kelembapan tanah
  if (soilMoisturePercent < 40) {
    digitalWrite(RELAY_2, LOW); // Nyalakan Pompa Penyiraman
  } else {
    digitalWrite(RELAY_2, HIGH); // Matikan Pompa Penyiraman
  }

  // Kontrol Relay untuk Lampu berdasarkan suhu
  if (temperature > 25) {
    digitalWrite(RELAY_1, LOW); // Nyalakan Lampu
  } else {
    digitalWrite(RELAY_1, HIGH); // Matikan Lampu
  }

  // Update tampilan setiap 2 detik
  if (millis() - lastUpdateTime > 2000) {
    lastUpdateTime = millis();
    display.clearDisplay();
    display.setTextSize(1);
    display.setCursor(0, 0);

    // Bergilir menampilkan data
    switch (displayState) {
      case 0:
        display.print(F("Waktu: "));
        display.print(now.hour());
        display.print(":");
        display.print(now.minute());
        display.print(":");
        display.println(now.second());
        break;
      case 1:
        display.print(F("Suhu: "));
        display.print(temperature);
        display.println(F(" C"));
        break;
      case 2:
        display.print(F("Kelembapan udara: "));
        display.print(humidity);
        display.println(F(" %"));
        break;
      case 3:
        display.print(F("Kelembapan tanah: "));
        display.print(soilMoisturePercent);
        display.println(F(" %"));
        break;
      case 4:
        display.print(F("Penyiraman: "));
        display.println(soilMoisturePercent < 40 ? F("ON") : F("OFF"));
        display.print(F("Pemupukan: "));
        display.println(fertilizing ? F("ON") : F("OFF"));
        display.print(F("Lampu: "));
        display.println(temperature > 25 ? F("ON") : F("OFF"));
        break;
    }

    display.display();
    displayState = (displayState + 1) % 5; // Update ke state berikutnya
  }
}
