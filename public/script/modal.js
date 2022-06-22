document.addEventListener("DOMContentLoaded", () => {
  document.addEventListener("click", (e) => {
    let modalBox = document.querySelector(".modalBox");
    let modale = document.querySelector(".modale");
    if (e.target.id.indexOf("delete") !== -1) {
      modalBox.style.display = "block";
      modale.style.display = "block";
      let idSuppr = e.target.id.substring(e.target.id.indexOf("-") + 1);
      let link = document.querySelector(".deleteLink");
      link.href = `/story/delete/${idSuppr}`;
    }
    if (
      e.target.className == "modalBox" ||
      e.target.className == "modale" ||
      e.target.className == "blueText modalText"
    ) {
      modalBox.style.display = "none";
      modale.style.display = "none";
    }
  });
});
