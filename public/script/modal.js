document.addEventListener("DOMContentLoaded", () => {
  console.log("Page chargée");
  document.addEventListener("click", (e) => {
    let modalBox = document.querySelector(".popUpBox");
    let modale = document.querySelector(".pop");
    if (e.target.id.indexOf("delete") !== -1) {
      modalBox.style.display = "block";
      modale.style.display = "block";
      let idSuppr = e.target.id.substring(e.target.id.indexOf("-") + 1);
      let link = document.querySelector(".deleteLink");
      link.href = `/story/delete/${idSuppr}`;
    }
    if (
      e.target.className == "popUpBox" ||
      e.target.className == "pop" ||
      e.target.className == "blueText modalText"
    ) {
      modalBox.style.display = "none";
      modale.style.display = "none";
    }
  });
});
