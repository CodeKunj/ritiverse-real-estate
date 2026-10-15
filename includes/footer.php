<?php
// Footer Component
global $config;
?>
<footer id="footer" class="fixed bottom-0 left-0 z-0 w-screen overflow-hidden bg-[#E0D4C6] px-[5%] text-[#1C1B1A] max-md:px-[6%] max-md:py-[10%] max-[1025px]:pt-[5%]">
  <div class="flex flex-col pt-[5%] pb-[2%]">
    <!-- Top Bar: Socials, Logo & Scroll Top -->
    <div class="relative mb-[7vw] flex w-full items-center justify-between max-md:gap-0 max-[1025px]:flex-col-reverse">
      <!-- Social Channels -->
      <div class="flex items-center justify-center gap-[1vw] max-md:mb-[5vw] max-md:gap-[3vw]">
        <a aria-label="Facebook" href="#" class="group">
          <div class="relative flex size-[2.7vw] group duration-300 transition-all ease-in-out items-center justify-center overflow-hidden rounded-full bg-[#1C1B1A] border border-[#1C1B1A] p-[0.75vw] max-md:h-[10vw] max-md:w-[10vw] max-md:p-[2.5vw] max-[1025px]:h-[5vw] max-[1025px]:w-[5vw] max-[1025px]:p-[1.3vw]">
            <img alt="Facebook" loading="lazy" width="24" height="24" decoding="async" class="h-full duration-300 transition-all ease-in-out group-hover:brightness-0 w-full object-contain relative z-2" src="assets/facebook.3rvk6c12nuvo6.svg"/>
            <span class="bg-white rounded-full scale-0 absolute origin-center group-hover:scale-100 w-full h-full duration-300 ease-in-out"></span>
          </div>
        </a>
        <a aria-label="Twitter" href="#" class="group">
          <div class="relative flex size-[2.7vw] group duration-300 transition-all ease-in-out items-center justify-center overflow-hidden rounded-full bg-[#1C1B1A] border border-[#1C1B1A] p-[0.75vw] max-md:h-[10vw] max-md:w-[10vw] max-md:p-[2.5vw] max-[1025px]:h-[5vw] max-[1025px]:w-[5vw] max-[1025px]:p-[1.3vw]">
            <img alt="Twitter" loading="lazy" width="24" height="24" decoding="async" class="h-full duration-300 transition-all ease-in-out group-hover:brightness-0 w-full object-contain relative z-2" src="assets/x.1r34ohx23d2is.svg"/>
            <span class="bg-white rounded-full scale-0 absolute origin-center group-hover:scale-100 w-full h-full duration-300 ease-in-out"></span>
          </div>
        </a>
        <a aria-label="Instagram" href="#" class="group">
          <div class="relative flex size-[2.7vw] group duration-300 transition-all ease-in-out items-center justify-center overflow-hidden rounded-full bg-[#1C1B1A] border border-[#1C1B1A] p-[0.75vw] max-md:h-[10vw] max-md:w-[10vw] max-md:p-[2.5vw] max-[1025px]:h-[5vw] max-[1025px]:w-[5vw] max-[1025px]:p-[1.3vw]">
            <img alt="Instagram" loading="lazy" width="24" height="24" decoding="async" class="h-full duration-300 transition-all ease-in-out group-hover:brightness-0 w-full object-contain relative z-2" src="assets/instagram.3q4vb5gjm6944.svg"/>
            <span class="bg-white rounded-full scale-0 absolute origin-center group-hover:scale-100 w-full h-full duration-300 ease-in-out"></span>
          </div>
        </a>
        <a aria-label="LinkedIn" href="#" class="group">
          <div class="relative flex size-[2.7vw] group duration-300 transition-all ease-in-out items-center justify-center overflow-hidden rounded-full bg-[#1C1B1A] border border-[#1C1B1A] p-[0.75vw] max-md:h-[10vw] max-md:w-[10vw] max-md:p-[2.5vw] max-[1025px]:h-[5vw] max-[1025px]:w-[5vw] max-[1025px]:p-[1.3vw]">
            <img alt="LinkedIn" loading="lazy" width="24" height="24" decoding="async" class="h-full duration-300 transition-all ease-in-out group-hover:brightness-0 w-full object-contain relative z-2" src="assets/linkedin.3r_ty6i-5rokn.svg"/>
            <span class="bg-white rounded-full scale-0 absolute origin-center group-hover:scale-100 w-full h-full duration-300 ease-in-out"></span>
          </div>
        </a>
      </div>

      <!-- Center Logo -->
      <div class="absolute top-1/2 left-1/2 flex -translate-x-1/2 -translate-y-1/2 items-center justify-center max-md:mb-[10vw] max-md:translate-y-0 max-[1025px]:static max-[1025px]:mb-[6vw] max-[1025px]:w-[40vw] max-[1025px]:translate-x-0 max-md:w-[55vw]">
        <a class="h-auto w-[14vw] max-md:w-[40vw] max-[1025px]:w-[24vw]" href="index.php">
          <img alt="RiTiVerse" loading="lazy" width="310" height="62" decoding="async" class="h-full w-full object-contain" src="assets/ritiverse-logo-black.svg"/>
        </a>
      </div>

      <!-- Scroll Top Button -->
      <div class="max-[1025px]:hidden">
        <a aria-label="Scroll Top" class="group cursor-pointer" href="#">
          <div class="relative flex h-[3vw] w-[3vw] items-center justify-center overflow-hidden rounded-full bg-[#1C1B1A] max-md:absolute max-md:top-[5%] max-md:right-[5%] max-md:h-[9.2vw] max-md:w-[9vw] max-[1025px]:h-[5vw] max-[1025px]:w-[5vw]">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-up absolute h-[1.5vw] w-[1.5vw] text-white transition-transform duration-500 ease-in-out group-hover:translate-y-[-180%] max-md:h-[4.5vw] max-md:w-[4.5vw] max-[1025px]:h-[2.4vw] max-[1025px]:w-[2.4vw]" aria-hidden="true"><path d="m18 15-6-6-6 6"></path></svg>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-up absolute h-[1.5vw] w-[1.5vw] translate-y-[180%] text-white transition-transform duration-500 ease-in-out group-hover:translate-y-0 max-md:h-[4.5vw] max-md:w-[4.5vw] max-[1025px]:h-[2.4vw] max-[1025px]:w-[2.4vw]" aria-hidden="true"><path d="m18 15-6-6-6 6"></path></svg>
          </div>
        </a>
      </div>
    </div>

    <!-- Center Contact Section -->
    <div class="flex flex-col items-center justify-center gap-3 text-[#1C1B1A]">
      <div class="w-fit">
        <a class="inline-flex items-center gap-2 no-underline transition-colors duration-500 ease-[cubic-bezier(0.625,0.05,0,1)] text-[#1C1B1A] hover:text-[#ca8216]" href="tel:+912269854100">
          <div class="mt-[0.3vw]">
            <span class="inline-block relative w-fit">
              <span data-text="+91 (022) 6985 4100" class="overflow-hidden relative inline-block leading-[1.2] max-[1025px]:text-[3vw] font-heading text-[3.4vw] font-medium leading-none max-md:text-[5.5vw] max-[1025px]:text-[5vw]">+91 (022) 6985 4100</span>
              <span aria-hidden="true" class="absolute left-0 w-full bg-current" style="bottom:-5%;height:0.18vw;transform:scaleX(0);transform-origin:left center;transition:transform 0.5s cubic-bezier(0.625, 0.05, 0, 1)"></span>
            </span>
          </div>
        </a>
      </div>
      <div class="w-fit">
        <a class="inline-flex items-center gap-2 no-underline transition-colors duration-500 ease-[cubic-bezier(0.625,0.05,0,1)] text-[#1C1B1A] hover:text-[#ca8216]" href="mailto:concierge@ritiverse.com">
          <div class="mt-[0.3vw]">
            <span class="inline-block relative w-fit">
              <span data-text="concierge@ritiverse.com" class="overflow-hidden relative inline-block leading-[1.2] max-[1025px]:text-[3vw] font-heading text-[3.2vw] font-medium max-md:text-[5.5vw] max-[1025px]:text-[5vw]">concierge@ritiverse.com</span>
              <span aria-hidden="true" class="absolute left-0 w-full bg-current" style="bottom:-5%;height:0.18vw;transform:scaleX(0);transform-origin:left center;transition:transform 0.5s cubic-bezier(0.625, 0.05, 0, 1)"></span>
            </span>
          </div>
        </a>
      </div>
    </div>

    <!-- Bottom Metadata: Copyright, Centered Address & Privacy Policy -->
    <div class="flex items-center justify-between pt-[7vw] max-md:gap-[5vw] max-[1025px]:flex-col max-[1025px]:gap-[3vw]">
      <!-- Copyright -->
      <div class="max-[1025px]:order-1 max-[1025px]:w-[70%]">
        <p class="text-[1vw] text-[#1C1B1A] normal-case max-md:text-[3.5vw] max-[1025px]:text-center max-[1025px]:text-[2.5vw]">
          © <?= date('Y') ?> RiTiVerse. All Rights Reserved
        </p>
      </div>

      <!-- Address (Centered on desktop and mobile) -->
      <div class="absolute bottom-[7%] max-[1025px]:bottom-[20%] -translate-x-1/2 left-1/2 w-[35%] max-[1025px]:ml-0 max-[1025px]:w-[80%] max-[1025px]:static max-[1025px]:translate-x-0 max-[1025px]:order-2">
        <p class="text-center text-[#1C1B1A] text-[1vw] leading-[1.2] max-md:text-[3.5vw] max-[1025px]:text-center max-[1025px]:text-[2.5vw]">
          Worli Sea Face, Worli, Mumbai, Maharashtra 400030, India
        </p>
      </div>

      <!-- Privacy Link -->
      <a class="inline-flex items-center gap-2 no-underline transition-colors duration-500 ease-[cubic-bezier(0.625,0.05,0,1)] max-[1025px]:order-3 max-[1025px]:pt-[2vh] text-[#1C1B1A] hover:text-[#ca8216]" href="#">
        <div class="mt-[0.3vw]">
          <span class="inline-block relative w-fit">
            <span data-text="Privacy Policy" class="overflow-hidden relative inline-block leading-[1.2] max-[1025px]:text-[3vw] text-[1vw] max-md:text-[3.2vw] max-[1025px]:text-[2.5vw]">Privacy Policy</span>
            <span aria-hidden="true" class="absolute left-0 w-full bg-current" style="bottom:15%;height:1px;transform:scaleX(0);transform-origin:left center;transition:transform 0.5s cubic-bezier(0.625, 0.05, 0, 1)"></span>
          </span>
        </div>
      </a>
    </div>
  </div>
</footer>
