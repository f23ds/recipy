$(document).ready(function () {
  const $form = $("#add_recipe");
  const $errorDiv = $("#adding-error");
  const $stepsContainer = $("#steps-container");
  const $addStepBtn = $("#add-step-btn");

  $errorDiv.hide().text("");

  let stepCount = 1;

  $form.on("submit", function (e) {
    e.preventDefault();
    $errorDiv.hide().text("");

    let no_adding = true;

    const title = $.trim($("input[name='title']").val());
    const diners = $.trim($("input[name='diners']").val());
    const description = $.trim($("#description").val());
    const ingredients = $.trim($("#ingredients").val());
    const image = $("#image")[0].files[0];

    const steps = $("input[name='instructions[]']")
      .map(function () {
        return $.trim($(this).val());
      })
      .get();

    function setError(id, condition, msg) {
      if (!condition) {
        $(`#error-${id}`).text(msg);
        no_adding = false;
      } else {
        $(`#error-${id}`).text("");
      }
    }

    setError("title", title, "❌ Please enter the title of the recipe.");
    setError("diners", diners, "❌ Please enter the amount of diners.");
    setError("description", description, "❌ Please enter a brief description.");
    setError("ingredients", ingredients, "❌ Please enter the ingredients.");
    setError(
      "instructions",
      steps.length > 0 && !steps.includes(""),
      "❌ All steps must be filled."
    );
    setError("image", image, "❌ Please enter an image.");

    if (!no_adding) return;

    // Enviar con FormData
    const formData = new FormData(this);

    $.ajax({
      url: "add-recipe.php",
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function (data) {
        if (data.success) {
          window.location.href = "dashboard.php";
        } else {
          $errorDiv.text(data.error).show();
        }
      },
      error: function () {
        $errorDiv.text("Something went wrong").show();
      },
    });
  });

  function updateRemoveButtons() {
    const $steps = $stepsContainer.find(".step-field");

    $steps.find(".remove-step-btn").remove();

    if ($steps.length > 1) {
      const $last = $steps.last();
      const $btn = $("<button>")
        .attr("type", "button")
        .addClass("remove-step-btn")
        .text("Remove")
        .on("click", function () {
          $last.remove();
          stepCount--;
          updateRemoveButtons();
        });

      $last.append($btn);
    }
  }

  $addStepBtn.on("click", function () {
    stepCount++;
    const $newStep = $(`
      <div class="step-field">
        <input type="text" name="instructions[]" placeholder="Step ${stepCount}..." />
      </div>
    `);

    $stepsContainer.append($newStep);
    updateRemoveButtons();
  });
});
