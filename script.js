document.getElementById("uploadForm").addEventListener("submit", async function (e) {
  e.preventDefault();

  const fileInput = document.getElementById("imageInput");
  const formData = new FormData();
  formData.append("image", fileInput.files[0]);

  const response = await fetch("remove_bg.php", {
    method: "POST",
    body: formData,
  });

  if (!response.ok) {
    const err = await response.json();
    if (err.redirect) {
      window.location.href = err.redirect;
    } else {
      alert(err.error || "An error occurred.");
    }
    return;
  }

  const blob = await response.blob();
  const url = URL.createObjectURL(blob);

  document.getElementById("result").innerHTML = `
    <h3>Result:</h3>
    <img src="${url}" alt="Background Removed" style="max-width: 300px;" />
    <br/><a href="${url}" download="no_bg.png">Download PNG</a>
  `;
});
