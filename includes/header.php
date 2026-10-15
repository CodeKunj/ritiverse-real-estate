<?php
// Header Component
global $config;
?>
<header class="lumera-header fixed inset-x-0 top-0 z-50 px-[5%] pt-[1vw] pb-[1vw] transform transition-all ease-in-out duration-500 max-[1025px]:px-[5%] max-[1025px]:py-[4%]">
  <div class="flex items-center justify-between">
    <!-- Logo -->
    <a class="relative z-60 flex items-center gap-[0.6vw] max-[1025px]:gap-[2vw]" href="/">
      <div class="h-fit w-44 max-md:w-32">
        <img alt="RiTiVerse" loading="lazy" width="310" height="62" decoding="async" class="h-full w-full object-contain" src="assets/ritiverse-logo-white.svg"/>
      </div>
    </a>

    <!-- Desktop Navigation -->
    <nav class="flex items-center gap-[3vw] max-[1025px]:hidden">
      <a class="inline-flex items-center gap-2 no-underline transition-colors duration-500 ease-[cubic-bezier(0.625,0.05,0,1)] text-white/90 hover:text-white" href="#about">
        <div class="mt-[0.3vw]">
          <span class="inline-block relative w-fit">
            <span data-text="About" class="overflow-hidden relative inline-block leading-[1.2] font-body text-[1.15vw] tracking-wide">About</span>
            <span aria-hidden="true" class="absolute left-0 w-full bg-current" style="bottom:15%;height:1px;transform:scaleX(0);transform-origin:left center;transition:transform 0.5s cubic-bezier(0.625, 0.05, 0, 1)"></span>
          </span>
        </div>
      </a>
      <a class="inline-flex items-center gap-2 no-underline transition-colors duration-500 ease-[cubic-bezier(0.625,0.05,0,1)] text-white/90 hover:text-white" href="#lumera-features">
        <div class="mt-[0.3vw]">
          <span class="inline-block relative w-fit">
            <span data-text="Features" class="overflow-hidden relative inline-block leading-[1.2] font-body text-[1.15vw] tracking-wide">Features</span>
            <span aria-hidden="true" class="absolute left-0 w-full bg-current" style="bottom:15%;height:1px;transform:scaleX(0);transform-origin:left center;transition:transform 0.5s cubic-bezier(0.625, 0.05, 0, 1)"></span>
          </span>
        </div>
      </a>
      <a class="inline-flex items-center gap-2 no-underline transition-colors duration-500 ease-[cubic-bezier(0.625,0.05,0,1)] text-white/90 hover:text-white" href="#project">
        <div class="mt-[0.3vw]">
          <span class="inline-block relative w-fit">
            <span data-text="Properties" class="overflow-hidden relative inline-block leading-[1.2] font-body text-[1.15vw] tracking-wide">Properties</span>
            <span aria-hidden="true" class="absolute left-0 w-full bg-current" style="bottom:15%;height:1px;transform:scaleX(0);transform-origin:left center;transition:transform 0.5s cubic-bezier(0.625, 0.05, 0, 1)"></span>
          </span>
        </div>
      </a>
      <a class="inline-flex items-center gap-2 no-underline transition-colors duration-500 ease-[cubic-bezier(0.625,0.05,0,1)] text-white/90 hover:text-white" href="#highlights">
        <div class="mt-[0.3vw]">
          <span class="inline-block relative w-fit">
            <span data-text="Locations" class="overflow-hidden relative inline-block leading-[1.2] font-body text-[1.15vw] tracking-wide">Locations</span>
            <span aria-hidden="true" class="absolute left-0 w-full bg-current" style="bottom:15%;height:1px;transform:scaleX(0);transform-origin:left center;transition:transform 0.5s cubic-bezier(0.625, 0.05, 0, 1)"></span>
          </span>
        </div>
      </a>
    </nav>

    <!-- Enquire CTA Button -->
    <div class="max-[1025px]:hidden">
      <a data-pressed="false" class="group/animated-fill relative inline-flex h-(--button-height) w-fit min-w-fit max-w-none cursor-pointer items-center justify-center overflow-hidden rounded-full px-[1.5vw] pr-[calc(var(--icon-circle)+var(--icon-right)+2vw)] whitespace-nowrap font-medium text-[1.15vw] leading-none [text-rendering:geometricPrecision] [--button-height:3.65vw] [--icon-circle:2.3vw] [--icon-right:0.55vw] [--circle-inset-y:calc((100%-var(--icon-circle))/2)] bg-(--btn-bg) text-(--btn-text) min-w-0! w-50" style="--btn-bg:#ffffff;--btn-text:#1c1b1a;--btn-border:transparent;--btn-fill-bg:#1c1b1a;--btn-fill-text:#ffffff;--btn-fill-bg-hover:#1c1b1a;--btn-fill-text-hover:#ffffff;--btn-arrow:#ffffff;--btn-arrow-hover:#ffffff;--btn-arrow-size:1.3vw;--btn-duration:0.45s;visibility:visible" href="javascript:void(0)" onclick="openEnquireModal()">
        <span class="relative z-1 pb-px">Enquire Now</span>
        <div aria-hidden="true" class="pointer-events-none absolute z-2 rounded-full bg-(--btn-fill-bg) inset-[var(--circle-inset-y)_var(--icon-right)_var(--circle-inset-y)_calc(100%-var(--icon-right)-var(--icon-circle))]"></div>
        <div aria-hidden="true" class="pointer-events-none absolute inset-0 z-2 flex items-center px-[1.5vw] pr-[calc(var(--icon-circle)+var(--icon-right)+2vw)] text-(--btn-fill-text) [clip-path:inset(var(--circle-inset-y)_var(--icon-right)_var(--circle-inset-y)_calc(100%-var(--icon-right)-var(--icon-circle)))]">
          <span class="relative z-1 pb-px whitespace-nowrap">Enquire Now</span>
        </div>
        <span class="pointer-events-none absolute right-(--icon-right) top-1/2 z-3 inline-flex h-(--icon-circle) w-(--icon-circle) shrink-0 -translate-y-1/2 items-center justify-center overflow-hidden rounded-full bg-[#1c1b1a] text-white" style="-webkit-mask-image:-webkit-radial-gradient(white, black);mask-image:radial-gradient(white, black)" aria-hidden="true">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right absolute left-1/2 top-1/2 size-(--btn-arrow-size) translate-x-[-170%] -translate-y-1/2 origin-center scale-0 text-current" aria-hidden="true"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right absolute left-1/2 top-1/2 size-(--btn-arrow-size) -translate-x-1/2 -translate-y-1/2 origin-center text-current" aria-hidden="true"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
        </span>
      </a>
    </div>

    <!-- Mobile Hamburger Menu Button -->
    <button type="button" aria-label="Open navigation menu" aria-expanded="false" class="relative z-60 hidden h-[6vw] w-[7vw] shrink-0 items-center justify-center max-[1025px]:flex max-md:h-[8vw] max-md:w-[9vw]">
      <span class="relative block h-[3vw] w-[5.5vw] max-md:h-[4vw] max-md:w-[7vw]">
        <span class="absolute left-0 top-[22%] h-px w-full bg-white transition-transform duration-300"></span>
        <span class="absolute left-0 top-[68%] h-px w-full bg-white transition-transform duration-300"></span>
      </span>
    </button>
  </div>

  <!-- Mobile Drawer -->
  <div id="mobileNavDrawer" class="fixed inset-0 z-50 hidden -translate-y-full h-dvh bg-[#1c1b1a] px-[5%] pb-[5vw] pt-[28vw] transition-transform duration-500 max-[1025px]:block max-md:py-[34vw]">
    <nav class="flex h-full flex-col justify-between">
      <div class="flex flex-col gap-[5vw]">
        <a class="inline-flex items-center gap-2 no-underline text-white" href="#about">
          <div class="mt-[0.3vw]">
            <span class="inline-block relative w-fit">
              <span data-text="About" class="overflow-hidden relative inline-block leading-[1.2] font-heading text-[9vw] font-medium leading-none max-md:text-[7vw]">About</span>
            </span>
          </div>
        </a>
        <a class="inline-flex items-center gap-2 no-underline text-white" href="#lumera-features">
          <div class="mt-[0.3vw]">
            <span class="inline-block relative w-fit">
              <span data-text="Features" class="overflow-hidden relative inline-block leading-[1.2] font-heading text-[9vw] font-medium leading-none max-md:text-[7vw]">Features</span>
            </span>
          </div>
        </a>
        <a class="inline-flex items-center gap-2 no-underline text-white" href="#project">
          <div class="mt-[0.3vw]">
            <span class="inline-block relative w-fit">
              <span data-text="Properties" class="overflow-hidden relative inline-block leading-[1.2] font-heading text-[9vw] font-medium leading-none max-md:text-[7vw]">Properties</span>
            </span>
          </div>
        </a>
        <a class="inline-flex items-center gap-2 no-underline text-white" href="#highlights">
          <div class="mt-[0.3vw]">
            <span class="inline-block relative w-fit">
              <span data-text="Locations" class="overflow-hidden relative inline-block leading-[1.2] font-heading text-[9vw] font-medium leading-none max-md:text-[7vw]">Locations</span>
            </span>
          </div>
        </a>
      </div>
      <a class="inline-flex items-center gap-2 no-underline h-[11vw] w-full justify-center rounded-full border border-[#ca8216] bg-white px-[6vw] text-[#1c1b1a] max-md:h-[15vw]" href="javascript:void(0)" onclick="openEnquireModal()">
        <div class="mt-[0.3vw]">
          <span class="inline-block relative w-fit">
            <span data-text="Enquire Now" class="overflow-hidden relative inline-block leading-[1.2] font-body text-[3vw] font-medium max-md:text-[4.2vw]">Enquire Now</span>
          </span>
        </div>
      </a>
    </nav>
  </div>
</header>
