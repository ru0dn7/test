document.addEventListener('DOMContentLoaded', function() {
  const accordionItems = document.querySelectorAll('.accordion .item');
  let activeItem = document.querySelector('.accordion .item.active');
  
  // Function to handle accordion item click
  function handleAccordionClick(item) {
    // If clicking the active item, do nothing
    if (item === activeItem) return;
    
    // Remove active class from current active item
    if (activeItem) {
      activeItem.classList.remove('active');
    }
    
    // Add active class to clicked item
    item.classList.add('active');
    activeItem = item;
  }
  
  // Add click event listeners to all items
  accordionItems.forEach(item => {
    item.addEventListener('click', () => handleAccordionClick(item));
  });

  // Parallax effect for banner background
  const banner = document.querySelector('.industry-banner');
  if (banner) {
    window.addEventListener('scroll', function() {
      const scrolled = window.pageYOffset;
      const bannerBg = banner.querySelector('.bg');
      if (bannerBg) {
        bannerBg.style.transform = `scale(1.1) translateY(${scrolled * 0.5}px)`;
      }
    });
  }
}); 