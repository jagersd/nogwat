# NogWat frontend

The NogWat frontend is an Ionic Vue application packaged for Android with Capacitor.

## Requirements

- Node.js 20.19 or newer
- Java 21
- Android SDK Platform 36 and build tools

## Development

```sh
npm ci
npm run dev
npm run build
npm test
npm run lint
```

The production API endpoint is configured in `.env.production`. Use `.env.example` as a template for local configuration. `VITE_*` values are embedded in the browser bundle, so they must never contain credentials or other secrets.

## Android release

The Android package ID is `nl.stefandejager.nogwat`; do not change it or Google Play will treat the output as a different app. The current release version code is `250601`, which is greater than the previous release code `250600`.

```sh
npm run android:build
```

The release AAB is written to `android/app/build/outputs/bundle/release/app-release.aab`. The build targets Android API 36.

### Signing

Create `android/key.properties` locally (it is ignored by Git):

```properties
storeFile=/absolute/path/to/your-upload-key.jks
storePassword=...
keyAlias=...
keyPassword=...
```

Do not commit the upload key, `key.properties`, generated AABs, or Android Studio/local SDK files. Confirm the highest version code in every Play Console track before upload and increase `versionCode` if necessary.
