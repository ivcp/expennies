import { Modal } from "bootstrap";

import { get, post } from "./ajax";

window.addEventListener("DOMContentLoaded", function () {
  const editCategoryModal = new Modal(
    document.getElementById("editCategoryModal")
  );

  document.querySelectorAll(".edit-category-btn").forEach((button) => {
    button.addEventListener("click", function (event) {
      const categoryId = event.currentTarget.getAttribute("data-id");
      get(`/categories/${categoryId}`).then((response) =>
        openEditCategoryModal(editCategoryModal, response)
      );
    });
  });

  document
    .querySelector(".save-category-btn")
    .addEventListener("click", async function (event) {
      const categoryId = event.currentTarget.getAttribute("data-id");

      post(`/categories/${categoryId}`, {
        name: editCategoryModal._element.querySelector('input[name="name"]')
          .value,
      }).then((response) => {
        console.log(response);
      });
    });
});

function getCsrfFields() {
  const csrfNameKey = document.querySelector("#csrfName").getAttribute("name");
  const csrfName = document.querySelector("#csrfName").content;
  const csrfValueKey = document
    .querySelector("#csrfValue")
    .getAttribute("name");
  const csrfValue = document.querySelector("#csrfValue").content;

  return {
    [csrfNameKey]: csrfName,
    [csrfValueKey]: csrfValue,
  };
}

function openEditCategoryModal(modal, { id, name }) {
  const nameInput = modal._element.querySelector('input[name="name"]');

  nameInput.value = name;

  modal._element
    .querySelector(".save-category-btn")
    .setAttribute("data-id", id);

  modal.show();
}
