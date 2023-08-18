#include <TimeLib.h>
#include <WiFi.h>
#include <HTTPClient.h>

const char* ssid = "EMB";
const char* password = "123456789";

const int ldrPin = 32;
const int interval = 1;
const int readingsPerInterval = 10;
const int startHour = 6;
const int startMinute = 0;
const int endHour = 18;
const int endMinute = 0;


void setup() {
  Serial.begin(115200);
  pinMode(ldrPin, INPUT);
  setTime(startHour, startMinute, 0, 1, 1, 2023);

  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
  }
  Serial.println("Connected to WiFi");
}

void loop() {
 time_t currentTime = now();
  int currentHour = hour(currentTime);
  int currentMinute = minute(currentTime);

  if (currentHour >= startHour && currentHour < endHour && currentMinute >= startMinute) {
    int elapsedMinutes = (currentHour - startHour) * 60 + (currentMinute - startMinute);

    if (elapsedMinutes % interval == 0) {
      for (int i = 0; i < readingsPerInterval; i++) {
        int ldrValue = analogRead(ldrPin);

        Serial.print("Nilai LDR: ");
        Serial.println(ldrValue);

        sendSensorData(ldrValue); // Mengirim data sensor ke server PHP

        delay(100);
      }

      delay(1000 * 60 * (interval - 1));
    }
  }

  if (currentHour >= endHour && currentMinute >= endMinute) {
    while (true) {
      // Berhenti dan tidak melakukan apa pun
    }
  }
}

void sendSensorData(int ldrValue) {
  HTTPClient http;

  String postData = "ldrValue=" + String(ldrValue);
  String serverURL = "http://192.168.183.73/EMB/sensor/send_sensor_data";
  Serial.print("Sending HTTP POST request to: ");
  Serial.println(serverURL);
  
  http.begin(serverURL);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  int httpResponseCode = http.POST(postData);
  if (httpResponseCode == 200) {
    Serial.println("Data sent successfully");
  } else {
    Serial.print("HTTP Error code: ");  
    Serial.println(httpResponseCode);
  }


  http.end();
}
