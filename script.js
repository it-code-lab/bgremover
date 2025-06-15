document.getElementById("uploadForm").addEventListener("submit", async function (e) {
  e.preventDefault();

  const fileInput = document.getElementById("imageInput");
  const submitBtn = document.getElementById("submitBtn");
  const loader = document.getElementById("loader");
  const resultDiv = document.getElementById("result");

  const formData = new FormData();
  formData.append("image", fileInput.files[0]);

  // Disable button and show loading state
  submitBtn.disabled = true;
  submitBtn.style.cursor = "not-allowed";
  loader.style.display = "block";
  resultDiv.innerHTML = "";

  try {
    const response = await fetch("remove_bg.php", {
      method: "POST",
      body: formData,
    });

    //DND - Uncomment the following lines to log the response details
    // console.log("Response status:", response.status);
    // console.log("Response headers:", response.headers); 
    // console.log("Response URL:", response.url);
    // console.log("Response type:", response.type);
    // console.log("Response ok:", response.ok);
    // console.log("Response redirected:", response.redirected);
    // console.log("Response status text:", response.statusText);


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
  } catch (error) {
    alert("Failed to process image: " + err.message);
  } finally {
    // Re-enable the button and hide loading state
    submitBtn.disabled = false;
    submitBtn.style.cursor = "pointer";
    loader.style.display = "none";
  }
});
