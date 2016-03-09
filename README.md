# waveform
Simple waveform generator for [soundcloud/waveformjs.git](https://github.com/soundcloud/waveformjs.git).
![Preview](https://oxmix.net/storage/e/1a/56dfe9057900b.png)

## Install `lame` for Debian/Ubuntu
Execute in console
```bash
sudo apt-get install lame
```

## Use
Get json result
```php
echo (new waveform('/path/to/file.mp3'))->json();
```
Set json result
```js
var waveform = new Waveform({
  container: element,
  data: jsonResult
});
```
