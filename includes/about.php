<?php
/**
 * About Component - Circular Arc 3D Carousel
 */
?>
<section class="overflow-hidden bg-[#E0D4C6] h-[84vw] max-[1025px]:h-[170vw] max-md:h-[220vw]" id="about">
  <div class="py-[5%] pt-[10%] max-md:pt-[20%] max-[1025px]:py-[15%]">
<?php
global $config;
$aboutCms = $config['cms']['about'] ?? [];
$aboutTitle = $aboutCms['title'] ?? "Real Estate,\nConsidered Differently";
$aboutP1 = $aboutCms['p1'] ?? "We believe finding the right home begins with understanding how you want to live.";
$aboutP2 = $aboutCms['p2'] ?? "From waterfront residences to private villas and landmark penthouses, we bring together exceptional properties with thoughtful guidance to make every decision feel considered, personal and effortless.";
?>
    <div class="w-full px-[5%] max-md:px-[6%] flex justify-between max-[1025px]:flex-col max-[1025px]:gap-[8vw]">
      <div data-copy-wrapper="true" class="opacity-100 w-[50%] max-md:w-[90%] max-[1025px]:w-[90%]">
        <h2 data-para-anim="true" class="w-full font-heading text-[3.8vw] font-medium! leading-[1.2] max-md:text-[8vw] text-[#1c1b1a] mt-[-1vw] max-[1025px]:text-[8vw]">
          <?= nl2br(htmlspecialchars($aboutTitle)) ?>
        </h2>
      </div>
      <div class="w-[40%] space-y-[1.5vw] text-[#1c1b1a] text-[1.25vw] max-md:text-[3.5vw] max-[1025px]:text-[3vw] max-[1025px]:w-full">
        <div data-copy-wrapper="true" class="opacity-100">
          <p data-para-anim="true"><?= htmlspecialchars($aboutP1) ?></p>
        </div>
        <div data-copy-wrapper="true" class="opacity-100">
          <p data-para-anim="true">
            <?= htmlspecialchars($aboutP2) ?>
          </p>
        </div>
      </div>
    </div>

    <div class="mt-[7vw] max-md:mt-[16vw]">
      <div class="relative w-full select-none" id="aboutCarouselTrack">
        <div class="sr-only" aria-live="polite" aria-atomic="true">Architectural Identity, slide 3 of 7</div>
        
        <div class="relative h-[30vw] w-full overflow-hidden transition-opacity duration-500 max-[1024px]:h-[47vw] max-md:h-[73vw] cursor-grab active:cursor-grabbing opacity-100">
          
          <!-- Slide 0 -->
          <div class="about-circle-slide" data-index="0" data-title="Waterfront Landmark" data-desc="Panoramic coastal views with floor-to-ceiling windows and direct beach access.">
            <img alt="Waterfront Landmark" draggable="false" decoding="async" class="pointer-events-none h-full w-full object-cover" src="assets/portfolio-img-new-1.3ulka13a-n5np.webp"/>
          </div>

          <!-- Slide 1 -->
          <div class="about-circle-slide" data-index="1" data-title="Serene Interiors" data-desc="Warm palette and refined natural textures crafted for quiet contemplative luxury.">
            <img alt="Serene Interiors" draggable="false" decoding="async" class="pointer-events-none h-full w-full object-cover" src="assets/portfolio-img-new-2.37pyq7v19f6jf.webp"/>
          </div>

          <!-- Slide 2 (Default Center) -->
          <div class="about-circle-slide is-active" data-index="2" data-title="Architectural Identity" data-desc="A bold modern structure designed to define India's coastal skyline with elegance and strength.">
            <img alt="Architectural Identity" draggable="false" decoding="async" class="pointer-events-none h-full w-full object-cover" src="assets/portfolio-img-new-3.43tkm-o7u7q3-.webp"/>
          </div>

          <!-- Slide 3 -->
          <div class="about-circle-slide" data-index="3" data-title="Wellness Retreat" data-desc="Holistic spa sanctuaries and private restorative zones inspired by nature.">
            <img alt="Wellness Retreat" draggable="false" loading="lazy" decoding="async" class="pointer-events-none h-full w-full object-cover" src="assets/portfolio-img-new-4.3576ru8031n7i.webp"/>
          </div>

          <!-- Slide 4 -->
          <div class="about-circle-slide" data-index="4" data-title="Poolside Living" data-desc="Seamless indoor-outdoor entertaining around an infinity reflection pool.">
            <img alt="Poolside Living" draggable="false" loading="lazy" decoding="async" class="pointer-events-none h-full w-full object-cover" src="assets/portfolio-img-new-5.2mgif7s46rv7a.webp"/>
          </div>

          <!-- Slide 5 -->
          <div class="about-circle-slide" data-index="5" data-title="Private Beach" data-desc="Exclusive pristine shoreline framed by gentle breezes and tranquil water.">
            <img alt="Private Beach" draggable="false" loading="lazy" decoding="async" class="pointer-events-none h-full w-full object-cover" src="assets/portfolio-img-new-6.0oecmcugucc0f.webp"/>
          </div>

          <!-- Slide 6 -->
          <div class="about-circle-slide" data-index="6" data-title="Water Pavilion" data-desc="Floating architectural cabanas offering an oasis above the azure horizon.">
            <img alt="Water Pavilion" draggable="false" loading="lazy" decoding="async" class="pointer-events-none h-full w-full object-cover" src="assets/portfolio-img-new-7.19toh7j68lyi7.webp"/>
          </div>

        </div>

        <!-- Carousel Navigation Buttons -->
        <button type="button" id="aboutPrevBtn" aria-label="Previous slide" class="absolute bottom-[-53%] group left-[calc(50%-2vw)] z-200 flex size-[3vw] -translate-x-1/2 -translate-y-1/2 items-center border border-black justify-center rounded-full text-black transition-colors max-[1024px]:left-[calc(50%-6vw)] max-md:left-[calc(50%-8vw)] max-[1024px]:bottom-[-70%] max-md:bottom-[-60%] max-[1024px]:size-[6vw] max-md:size-[11vw]">
          <svg class="size-4 relative group-hover:text-white duration-300 ease-in-out" width="22" height="15" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7.92891 14.3595C8.14461 14.3595 8.36032 14.2867 8.53061 14.1309C8.85983 13.8295 8.85983 13.3308 8.53061 13.0294L2.24121 7.27302L8.53061 1.51662C8.85983 1.21529 8.85983 0.716535 8.53061 0.415206C8.20138 0.113877 7.65645 0.113877 7.32722 0.415206L0.43613 6.72232C0.106902 7.02365 0.106902 7.5224 0.43613 7.82373L7.32722 14.1309C7.49751 14.2867 7.71321 14.3595 7.92891 14.3595Z" fill="currentColor" stroke="currentColor" stroke-width="0.378445"></path>
            <path d="M1.23135 8.04981L20.3379 8.04981C20.8034 8.04981 21.1894 7.69653 21.1894 7.27051C21.1894 6.84449 20.8034 6.49121 20.3379 6.49121L1.23135 6.49121C0.765886 6.49121 0.379896 6.84449 0.379896 7.27051C0.379896 7.69653 0.765886 8.04981 1.23135 8.04981Z" fill="currentColor" stroke="currentColor" stroke-width="0.378445"></path>
          </svg>
          <span class="bg-black rounded-full scale-0 absolute origin-center group-hover:scale-100 w-full h-full duration-300 ease-in-out z-[-1]"></span>
        </button>

        <button type="button" id="aboutNextBtn" aria-label="Next slide" class="absolute bottom-[-53%] group border border-black left-[calc(50%-1vw)] z-200 flex size-[3vw] translate-x-1/2 -translate-y-1/2 items-center justify-center rounded-full text-black transition-colors max-[1024px]:left-[calc(50%-3vw)] max-[1024px]:bottom-[-70%] max-md:bottom-[-60%] max-md:left-[calc(50%-5vw)] max-[1024px]:size-[6vw] max-md:size-[11vw]">
          <svg class="size-4 relative group-hover:text-white duration-300 ease-in-out" width="22" height="15" viewBox="0 0 22 15" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M13.4497 14.3595C13.234 14.3595 13.0183 14.2867 12.848 14.1309C12.5188 13.8295 12.5188 13.3308 12.848 13.0294L19.1374 7.27302L12.848 1.51662C12.5188 1.21529 12.5188 0.716535 12.848 0.415206C13.1772 0.113877 13.7222 0.113877 14.0514 0.415206L20.9425 6.72232C21.2717 7.02365 21.2717 7.5224 20.9425 7.82373L14.0514 14.1309C13.8811 14.2867 13.6654 14.3595 13.4497 14.3595Z" fill="currentColor" stroke="currentColor" stroke-width="0.378445"></path>
            <path d="M20.1473 8.04981L1.04066 8.04981C0.575201 8.04981 0.189209 7.69653 0.189209 7.27051C0.189209 6.84449 0.575201 6.49121 1.04066 6.49121L20.1473 6.49121C20.6127 6.49121 20.9987 6.84449 20.9987 7.27051C20.9987 7.69653 20.6127 8.04981 20.1473 8.04981Z" fill="currentColor" stroke="currentColor" stroke-width="0.378445"></path>
          </svg>
          <span class="bg-black rounded-full scale-0 absolute origin-center group-hover:scale-100 w-full h-full duration-300 ease-in-out z-[-1]"></span>
        </button>

      </div>

      <!-- Active Slide Title & Description -->
      <div data-para-anim="true" class="mx-auto mt-[1vw] max-md:mt-[5vw] max-w-[24vw] text-center max-[1025px]:max-w-[80vw] max-md:space-y-[2vw]">
        <div id="aboutTextWrapper" style="opacity:1; transition: opacity 0.3s ease;">
          <p id="aboutActiveTitle" class="font-heading text-[2.3vw] leading-[1.2] font-medium max-md:text-[5vw] text-[#1c1b1a] max-[1025px]:text-[5vw]">
            Architectural Identity
          </p>
          <p id="aboutActiveDesc" class="mt-[1vw] w-[90%] text-center mx-auto text-[1.01vw] text-[#1C1B1A] max-[1025px]:text-[2.5vw] max-md:text-[3vw]">
            A bold modern structure designed to define India's coastal skyline with elegance and strength.
          </p>
        </div>
      </div>

    </div>
  </div>
</section>
