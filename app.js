document.addEventListener("DOMContentLoaded", function () {
    const contentField = document.getElementById("content");

    if (contentField) {
        const counter = document.createElement("p");
        counter.className = "character-counter";

        contentField.insertAdjacentElement("afterend", counter);

        function updateCharacterCount() {
            counter.textContent = "Characters: " + contentField.value.length;
        }

        contentField.addEventListener("input", updateCharacterCount);
        updateCharacterCount();
    }

    const deleteForm = document.getElementById("delete-form");

    if (deleteForm) {
        deleteForm.addEventListener("submit", function (event) {
            const confirmed = confirm(
                "Are you sure you want to permanently delete this post?"
            );

            if (!confirmed) {
                event.preventDefault();
            }
        });
    }
});