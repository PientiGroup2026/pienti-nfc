#!/usr/bin/env python3
"""Otel sayfalarini ve otel secim sayfasini uretir: python3 build.py"""
import os, html

ICONS = {
 "sheet":'<path d="M14 2H7a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7z"/><path d="M14 2v5h5"/><path d="M9 13h6M9 17h6"/>',
 "book" :'<path d="M2 4.5h7a3 3 0 0 1 3 3V20a2.5 2.5 0 0 0-2.5-2.5H2z"/><path d="M22 4.5h-7a3 3 0 0 0-3 3V20a2.5 2.5 0 0 1 2.5-2.5H22z"/>',
 "insta":'<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r="1" fill="currentColor" stroke="none"/>',
 "mice" :'<path d="M3 4h18v11H3z"/><path d="M12 15v4"/><path d="M8 21h8"/><path d="M8 11l2.5-2.5L13 11l3-3.5"/>',
 "web"  :'<circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3a15 15 0 0 1 0 18a15 15 0 0 1 0-18"/>',
}

HOTELS = [
 dict(slug="yunak", name="Yunak Evleri", logo="yunak-logo.png",
      accent="#c9a227", ga="rgba(201,162,39,.30)", gb="rgba(120,96,58,.26)",
      tagline="Kapadokya'nın kalbinde, tarihi mağara odalarında bir konaklama.",
      links=[("mice","MICE / Toplantı & Etkinlik","Kurumsal toplantı, kongre ve özel organizasyonlar","https://pienti.com/meetings-incentives.html"),
             ("sheet","Otel Künyesi","Fact Sheet (İngilizce)","https://publuu.com/flip-book/877949/2410703"),
             ("book","Dijital Katalog","Odalar, hizmetler ve görseller","https://publuu.com/flip-book/877949/1925813"),
             ("insta","Instagram","@yunakevleri","https://www.instagram.com/yunakevleri/"),
             ("web","Web Sitesi","yunak.com","https://yunak.com/")]),
 dict(slug="asmalikonak", name="Asmalı Konak Cave Suites", logo="asmali-logo.png",
      accent="#bb9358", ga="rgba(187,147,88,.30)", gb="rgba(86,110,190,.22)",
      tagline="İki asırlık konak, iki sakin avlu ve taş mağara süitler.",
      links=[("mice","MICE / Toplantı & Etkinlik","Kurumsal toplantı, kongre ve özel organizasyonlar","https://pienti.com/meetings-incentives.html"),
             ("sheet","Otel Künyesi","Fact Sheet (İngilizce)","https://publuu.com/flip-book/877949/2410701"),
             ("book","Dijital Katalog","Odalar, hizmetler ve görseller","https://publuu.com/flip-book/877949/2118826"),
             ("insta","Instagram","@asmalikonakcavesuites","https://www.instagram.com/asmalikonakcavesuites/"),
             ("web","Web Sitesi","asmalikonak.com.tr","https://www.asmalikonak.com.tr/")]),
 dict(slug="mithra", name="Mithra Cave Hotel", logo="mithra-logo.svg",
      accent="#c0574f", ga="rgba(192,87,79,.30)", gb="rgba(112,9,6,.30)",
      tagline="Göreme'nin manzarasına açılan mağara odalar ve teraslar.",
      links=[("mice","MICE / Toplantı & Etkinlik","Kurumsal toplantı, kongre ve özel organizasyonlar","https://pienti.com/meetings-incentives.html"),
             ("sheet","Otel Künyesi","Fact Sheet (İngilizce)","https://publuu.com/flip-book/877949/2410702"),
             ("insta","Instagram","@mithracavehotel","https://www.instagram.com/mithracavehotel/"),
             ("web","Web Sitesi","mithracavehotel.com","https://www.mithracavehotel.com/")]),
 dict(slug="misty", name="The Misty Cave Hotel", logo="misty-logo.png",
      accent="#a9b4c0", ga="rgba(169,180,192,.24)", gb="rgba(80,96,116,.28)",
      tagline="Kapadokya manzarasına bakan sıcak ve sakin mağara odalar.",
      links=[("mice","MICE / Toplantı & Etkinlik","Kurumsal toplantı, kongre ve özel organizasyonlar","https://pienti.com/meetings-incentives.html"),
             ("sheet","Otel Künyesi","Fact Sheet (İngilizce)","https://publuu.com/flip-book/877949/2410700"),
             ("insta","Instagram","@themistycavehotel","https://www.instagram.com/themistycavehotel/"),
             ("web","Web Sitesi","themistycavehotel.com","https://themistycavehotel.com/")]),
]

def card(icon, title, desc, href):
    return (f'      <a class="card fade" href="{href}" data-go>\n'
            f'        <span class="ico" aria-hidden="true"><svg viewBox="0 0 24 24">{ICONS[icon]}</svg></span>\n'
            f'        <span class="txt"><b>{html.escape(title)}</b><span>{html.escape(desc)}</span></span>\n'
            f'        <span class="arrow" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg></span>\n'
            f'      </a>')

OVERLAY = ('  <div id="go" role="status" aria-live="polite">\n'
           '    <div class="spin" aria-hidden="true"></div>\n'
           '    <div>Yönlendiriliyorsunuz…</div>\n'
           '  </div>')

for h in HOTELS:
    os.makedirs(h["slug"], exist_ok=True)
    cards = "\n".join(card(*l) for l in h["links"])
    open(f'{h["slug"]}/index.html', "w").write(f'''<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>{html.escape(h["name"])}</title>
<meta name="description" content="{html.escape(h["name"])} — {html.escape(h["tagline"])}">
<meta name="theme-color" content="#0b0b0e">
<meta property="og:title" content="{html.escape(h["name"])}">
<meta property="og:description" content="{html.escape(h["tagline"])}">
<link rel="stylesheet" href="../assets/style.css">
<style>:root{{--accent:{h["accent"]};--glow-a:{h["ga"]};--glow-b:{h["gb"]}}}</style>
</head>
<body>
  <div class="bg"></div><div class="grid"></div><div class="grain"></div>

  <main class="wrap">
    <div class="fade">
      <img class="logo" src="../assets/{h["logo"]}" alt="{html.escape(h["name"])}">
      <div class="rule"></div>
      <p class="sub">{html.escape(h["tagline"])}</p>
    </div>

    <nav class="links">
{cards}
    </nav>

    <a class="back fade" href="../">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
      Diğer otellerimiz
    </a>

    <footer class="fade">Pienti Group</footer>
  </main>

{OVERLAY}
<script src="../assets/go.js"></script>
</body>
</html>
''')
    print("yazıldı:", h["slug"] + "/index.html")

hotel_cards = "\n".join(
  f'      <a class="card hotel fade" href="{h["slug"]}/">\n'
  f'        <img src="assets/{h["logo"]}" alt="{html.escape(h["name"])}">\n'
  f'      </a>' for h in HOTELS)

open("index.html", "w").write(f'''<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>Otellerimiz</title>
<meta name="description" content="Kapadokya'daki otellerimiz">
<meta name="theme-color" content="#0b0b0e">
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <div class="bg"></div><div class="grid"></div><div class="grain"></div>

  <main class="wrap">
    <div class="fade">
      <p class="sub" style="margin-top:0">Kapadokya'daki otellerimiz.<br>Lütfen kaldığınız oteli seçin.</p>
    </div>

    <nav class="links">
{hotel_cards}
    </nav>

    <footer class="fade">Pienti Group</footer>
  </main>
<script src="assets/go.js"></script>
</body>
</html>
''')
print("yazıldı: index.html")
