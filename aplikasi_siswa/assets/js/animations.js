document.addEventListener("DOMContentLoaded", function () {
  // Add fade-in animation to cards
  const cards = document.querySelectorAll(".card");
  cards.forEach((card, index) => {
    setTimeout(() => {
      card.classList.add("fade-in");
    }, index * 100);
  });

  // Add slide-in animation to table rows
  const tableRows = document.querySelectorAll("tbody tr");
  tableRows.forEach((row, index) => {
    setTimeout(() => {
      row.classList.add("slide-in");
    }, index * 50);
  });

  // Add hover effect to buttons
  const buttons = document.querySelectorAll(".btn");
  buttons.forEach((button) => {
    button.addEventListener("mouseenter", function () {
      this.style.transform = "translateY(-2px)";
    });

    button.addEventListener("mouseleave", function () {
      this.style.transform = "translateY(0)";
    });
  });
});
