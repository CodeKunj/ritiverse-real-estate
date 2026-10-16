<?php
// Hero Component
global $config;
$heroBg = !empty($config['cms']['hero']['bg_image']) ? $config['cms']['hero']['bg_image'] : 'assets/hero.1vkt8ape_254z.webp';
$headline1 = !empty($config['cms']['hero']['headline_1']) ? $config['cms']['hero']['headline_1'] : 'Exceptional';
$headline2 = !empty($config['cms']['hero']['headline_2']) ? $config['cms']['hero']['headline_2'] : 'Living In India';
$heroDesc = !empty($config['cms']['hero']['description']) ? $config['cms']['hero']['description'] : ($config['site']['description'] ?? '');
$btn1Text = !empty($config['cms']['hero']['btn1_text']) ? $config['cms']['hero']['btn1_text'] : 'Explore Our Collection';
$btn1Url = !empty($config['cms']['hero']['btn1_url']) ? $config['cms']['hero']['btn1_url'] : '#lumera-features';
$btn2Text = !empty($config['cms']['hero']['btn2_text']) ? $config['cms']['hero']['btn2_text'] : 'Speak With an Advisor';
$stats = $config['stats'] ?? [];
?>
<section id="hero" class="relative overflow-hidden">
  <div class="relative isolate flex min-h-[200vh] w-screen flex-col gap-[5vw] px-[5%] py-[3%] max-[1025px]:min-h-[240vw] max-[1025px]:gap-[8vw] max-[1025px]:py-[7%]">
    <div class="pointer-events-none absolute inset-0 overflow-hidden z-[-1]">
      <div class="relative h-full w-full">
        <img alt="<?= htmlspecialchars($config['site']['name'] ?? 'RiTiVerse') ?> luxury residences in India" fetchPriority="high" loading="eager" decoding="async" data-nimg="fill" class="h-full w-full object-cover object-top" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" src="<?= htmlspecialchars($heroBg) ?>"/>
      </div>
    </div>
    <div class="relative z-10 flex h-screen flex-col justify-end gap-[8vw] max-[1025px]:gap-[12vw]">
      <div class="flex flex-col gap-[3vw] max-[1025px]:gap-[6vw] mb-30">
        <div data-copy-wrapper="true">
          <h1 class="font-heading font-medium! flex flex-col gap-[1vw] text-[10vw] leading-[0.94] uppercase text-[#EEEEEE] max-[1025px]:gap-[4vw] max-md:text-[12vw] max-[1025px]:text-[10vw]">
            <span><?= htmlspecialchars($headline1) ?></span>
            <div class="flex items-end gap-[1.8vw] max-[1025px]:flex-col max-[1025px]:items-start max-[1025px]:gap-[4vw]">
              <span><?= htmlspecialchars($headline2) ?></span>
              <a class="group relative flex h-[6.5vw] w-[6.5vw] items-center justify-center self-center overflow-hidden rounded-full border-4 border-white text-white opacity-100 transition-colors max-[1025px]:h-[18vw] max-[1025px]:w-[18vw] max-[1025px]:self-start" aria-label="Scroll to about section" href="#about">
                <img alt="" loading="lazy" width="32" height="45" decoding="async" data-nimg="1" class="absolute h-[2.3vw] w-auto object-contain transition-transform duration-500 ease-in-out group-hover:translate-y-[180%] max-[1025px]:h-[7vw]" style="color:transparent" src="assets/arrow-down.14f_bny3qfvye.svg"/>
                <img alt="" loading="lazy" width="32" height="45" decoding="async" data-nimg="1" class="absolute h-[2.3vw] w-auto translate-y-[-180%] object-contain transition-transform duration-500 ease-in-out group-hover:translate-y-0 max-[1025px]:h-[7vw]" style="color:transparent" src="assets/arrow-down.14f_bny3qfvye.svg"/>
              </a>
            </div>
          </h1>
        </div>
      </div>
    </div>
    <div class="relative z-10 flex min-h-screen pb-20 flex-col justify-between gap-[8vw] max-[1025px]:min-h-[110vw] max-[1025px]:gap-[10vw]">
      <div class="flex flex-col gap-[4vw] max-[1025px]:gap-[8vw]">
        <div class="w-[38vw] text-white max-[1025px]:w-full">
          <div data-copy-wrapper="true" class="opacity-100">
            <p class="text-[1.45vw] max-[1025px]:text-[3vw] max-md:leading-[1.4] max-md:text-[5vw] leading-[1.65] text-[#EEEEEE]">
              <?= htmlspecialchars($heroDesc) ?>
            </p>
          </div>
        </div>
        <div class="flex items-center gap-[1vw] max-md:flex-col max-md:items-start max-md:gap-[4vw] max-md:w-[80%] max-[1025px]:flex-wrap max-[1025px]:gap-[3vw]">
          <div>
            <a data-pressed="false" class="group/animated-fill relative inline-flex h-(--button-height) w-fit min-w-fit max-w-none cursor-pointer items-center justify-center overflow-hidden rounded-full px-[1.5vw] pr-[calc(var(--icon-circle)+var(--icon-right)+2vw)] whitespace-nowrap font-medium text-[1.15vw] leading-none [text-rendering:geometricPrecision] [--button-height:3.65vw] [--icon-circle:2.3vw] [--icon-right:0.55vw] [--circle-inset-y:calc((100%-var(--icon-circle))/2)] max-[1025px]:h-(--button-height) max-[1025px]:px-[5vw] max-[1025px]:pr-[calc(var(--icon-circle)+var(--icon-right)+4vw)] max-[1025px]:text-[3vw] max-[1025px]:font-normal max-[1025px]:[--button-height:11vw] max-[1025px]:[--icon-circle:8vw] max-[1025px]:[--icon-right:1.5vw] max-md:h-(--button-height) max-md:px-[7vw] max-md:pr-[calc(var(--icon-circle)+var(--icon-right)+5vw)] max-md:text-[4.2vw] max-md:[--button-height:15vw] max-md:[--icon-circle:11vw] max-md:[--icon-right:2vw] bg-(--btn-bg) text-(--btn-text) max-md:w-[70vw]" style="--btn-bg:#ffffff;--btn-text:#1c1b1a;--btn-border:transparent;--btn-fill-bg:#1c1b1a;--btn-fill-text:#ffffff;--btn-fill-bg-hover:#1c1b1a;--btn-fill-text-hover:#ffffff;--btn-arrow:#ffffff;--btn-arrow-hover:#ffffff;--btn-arrow-size:1.3vw;--btn-arrow-size-tablet:5vw;--btn-arrow-size-mobile:6.5vw;--btn-duration:0.45s;visibility:visible" href="<?= htmlspecialchars($btn1Url) ?>">
              <span class="relative z-1 pb-px"><?= htmlspecialchars($btn1Text) ?></span>
              <div aria-hidden="true" class="pointer-events-none absolute z-2 rounded-full bg-(--btn-fill-bg) inset-[var(--circle-inset-y)_var(--icon-right)_var(--circle-inset-y)_calc(100%-var(--icon-right)-var(--icon-circle))]"></div>
              <div aria-hidden="true" class="pointer-events-none absolute inset-0 z-2 flex items-center px-[1.5vw] pr-[calc(var(--icon-circle)+var(--icon-right)+2vw)] text-(--btn-fill-text) [clip-path:inset(var(--circle-inset-y)_var(--icon-right)_var(--circle-inset-y)_calc(100%-var(--icon-right)-var(--icon-circle)))] max-[1025px]:px-[5vw] max-[1025px]:pr-[calc(var(--icon-circle)+var(--icon-right)+4vw)] max-md:px-[7vw] max-md:pr-[calc(var(--icon-circle)+var(--icon-right)+5vw)]">
                <span class="relative z-1 pb-px whitespace-nowrap"><?= htmlspecialchars($btn1Text) ?></span>
              </div>
              <span class="pointer-events-none absolute right-(--icon-right) top-1/2 z-3 inline-flex h-(--icon-circle) w-(--icon-circle) shrink-0 -translate-y-1/2 items-center justify-center overflow-hidden rounded-full bg-(--btn-fill-bg) text-(--btn-arrow)" style="-webkit-mask-image:-webkit-radial-gradient(white, black);mask-image:radial-gradient(white, black)" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right absolute left-1/2 top-1/2 size-(--btn-arrow-size) max-[1025px]:size-(--btn-arrow-size-tablet) max-md:size-(--btn-arrow-size-mobile) translate-x-[-170%] -translate-y-1/2 origin-center scale-0 text-current" aria-hidden="true"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right absolute left-1/2 top-1/2 size-(--btn-arrow-size) max-[1025px]:size-(--btn-arrow-size-tablet) max-md:size-(--btn-arrow-size-mobile) -translate-x-1/2 -translate-y-1/2 origin-center text-current" aria-hidden="true"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
              </span>
            </a>
          </div>
          <div>
            <a data-pressed="false" class="group/animated-fill relative inline-flex h-(--button-height) w-fit min-w-fit max-w-none cursor-pointer items-center justify-center overflow-hidden rounded-full border border-(--btn-border) px-[1.5vw] pr-[calc(var(--icon-circle)+var(--icon-right)+2vw)] whitespace-nowrap font-medium text-[1.15vw] leading-none [text-rendering:geometricPrecision] [--button-height:3.65vw] [--icon-circle:2.3vw] [--icon-right:0.55vw] [--circle-inset-y:calc((100%-var(--icon-circle))/2)] max-[1025px]:h-(--button-height) max-[1025px]:px-[5vw] max-[1025px]:pr-[calc(var(--icon-circle)+var(--icon-right)+4vw)] max-[1025px]:text-[3vw] max-[1025px]:font-normal max-[1025px]:[--button-height:11vw] max-[1025px]:[--icon-circle:8vw] max-[1025px]:[--icon-right:1.5vw] max-md:h-(--button-height) max-md:px-[7vw] max-md:pr-[calc(var(--icon-circle)+var(--icon-right)+5vw)] max-md:text-[4.2vw] max-md:[--button-height:15vw] max-md:[--icon-circle:11vw] max-md:[--icon-right:2vw] bg-(--btn-bg) text-(--btn-text) border max-md:w-[70vw] border-white shadow-2xl drop-shadow-2xl" style="--btn-bg:transparent;--btn-text:#ffffff;--btn-border:transparent;--btn-fill-bg:#ffffff;--btn-fill-text:#1c1b1a;--btn-fill-bg-hover:#ffffff;--btn-fill-text-hover:#1c1b1a;--btn-arrow:#1c1b1a;--btn-arrow-hover:#1c1b1a;--btn-arrow-size:1.5vw;--btn-arrow-size-tablet:5vw;--btn-arrow-size-mobile:6.5vw;--btn-duration:0.45s;visibility:visible" href="javascript:void(0)" onclick="openEnquireModal()">
              <span class="relative z-1 pb-px"><?= htmlspecialchars($btn2Text) ?></span>
              <div aria-hidden="true" class="pointer-events-none absolute z-2 rounded-full bg-(--btn-fill-bg) inset-[var(--circle-inset-y)_var(--icon-right)_var(--circle-inset-y)_calc(100%-var(--icon-right)-var(--icon-circle))]"></div>
              <div aria-hidden="true" class="pointer-events-none absolute inset-0 z-2 flex items-center px-[1.5vw] pr-[calc(var(--icon-circle)+var(--icon-right)+2vw)] text-(--btn-fill-text) [clip-path:inset(var(--circle-inset-y)_var(--icon-right)_var(--circle-inset-y)_calc(100%-var(--icon-right)-var(--icon-circle)))] max-[1025px]:px-[5vw] max-[1025px]:pr-[calc(var(--icon-circle)+var(--icon-right)+4vw)] max-md:px-[7vw] max-md:pr-[calc(var(--icon-circle)+var(--icon-right)+5vw)]">
                <span class="relative z-1 pb-px whitespace-nowrap"><?= htmlspecialchars($btn2Text) ?></span>
              </div>
              <span class="pointer-events-none absolute right-(--icon-right) top-1/2 z-3 inline-flex h-(--icon-circle) w-(--icon-circle) shrink-0 -translate-y-1/2 items-center justify-center overflow-hidden rounded-full bg-(--btn-fill-bg) text-(--btn-arrow)" style="-webkit-mask-image:-webkit-radial-gradient(white, black);mask-image:radial-gradient(white, black)" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right absolute left-1/2 top-1/2 size-(--btn-arrow-size) max-[1025px]:size-(--btn-arrow-size-tablet) max-md:size-(--btn-arrow-size-mobile) translate-x-[-170%] -translate-y-1/2 origin-center scale-0 text-current" aria-hidden="true"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right absolute left-1/2 top-1/2 size-(--btn-arrow-size) max-[1025px]:size-(--btn-arrow-size-tablet) max-md:size-(--btn-arrow-size-mobile) -translate-x-1/2 -translate-y-1/2 origin-center text-current" aria-hidden="true"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
              </span>
            </a>
          </div>
        </div>
      </div>

      <!-- Hero Stats Grid -->
      <div class="grid grid-cols-4 gap-[5vw] self-stretch max-[1025px]:grid-cols-2 max-md:gap-[3vw] max-[1025px]:gap-[3vw]">
        <?php foreach ($stats as $st): ?>
          <div class="rounded-[1.2vw] bg-white/5 px-[1.5vw] py-[2vw] max-md:py-[5vw] max-[1025px]:py-[4vw] font-heading text-center backdrop-blur-2xl max-[1025px]:rounded-[4vw] flex flex-col justify-between max-md:min-h-[12vh] min-h-[19vh] border border-[#F9F9F91A]">
            <p class="inline-flex items-baseline justify-center gap-[0.25vw] text-[2.9vw] font-medium leading-none text-white max-[1025px]:gap-[1vw] max-[1025px]:text-[6vw]">
              <span><?= htmlspecialchars($st['value']) ?></span>
              <?php if (!empty($st['unit'])): ?>
                <span class="mb-[0.15vw] text-[0.75vw] font-normal leading-none max-[1025px]:mb-[0.4vw] max-[1025px]:text-[2.5vw]"><?= htmlspecialchars($st['unit']) ?></span>
              <?php endif; ?>
            </p>
            <p class="text-[1.1vw] text-white max-[1025px]:text-[3vw]"><?= htmlspecialchars($st['label']) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
