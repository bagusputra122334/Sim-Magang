"use strict";

(function () {
  var sidebarStorageKey = "adminHMD.sidebarMini";
  var themeStorageKey = "adminHMD.colorTheme";
  var desktopMedia = "(min-width: 992px)";

  function onReady(callback) {
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", callback);
      return;
    }

    callback();
  }

  function isDesktop() {
    return window.matchMedia(desktopMedia).matches;
  }

  function canUseStorage() {
    try {
      var testKey = sidebarStorageKey + ".test";
      window.localStorage.setItem(testKey, "1");
      window.localStorage.removeItem(testKey);
      return true;
    } catch (error) {
      return false;
    }
  }

  function getSavedMiniState(storageAvailable) {
    if (!storageAvailable) {
      return false;
    }

    return window.localStorage.getItem(sidebarStorageKey) === "true";
  }

  function saveMiniState(storageAvailable, isMini) {
    if (storageAvailable) {
      window.localStorage.setItem(sidebarStorageKey, String(isMini));
    }
  }

  function getPreferredTheme(storageAvailable) {
    var savedTheme = storageAvailable ? window.localStorage.getItem(themeStorageKey) : "";

    if (savedTheme === "dark" || savedTheme === "light") {
      return savedTheme;
    }

    if (window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches) {
      return "dark";
    }

    return "light";
  }

  onReady(function () {
    var body = document.body;
    var sidebarToggle = document.querySelector("[data-sidebar-toggle]");
    var themeToggles = document.querySelectorAll("[data-theme-toggle]");
    var themeIcons = document.querySelectorAll("[data-theme-icon]");
    var closeButtons = document.querySelectorAll("[data-sidebar-close]");
    var sidebarLinks = document.querySelectorAll(".sidebar-nav .nav-link");
    var mediaQuery = window.matchMedia(desktopMedia);
    var storageAvailable = canUseStorage();

    function initValidation() {
      var forms = document.querySelectorAll(".needs-validation");

      Array.prototype.forEach.call(forms, function (form) {
        form.addEventListener("submit", function (event) {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
          }

          form.classList.add("was-validated");
        });
      });
    }

    function initTableSearch() {
      var searchInputs = document.querySelectorAll("[data-table-search]");

      Array.prototype.forEach.call(searchInputs, function (input) {
        var tableId = input.getAttribute("data-table-search");
        var table = document.getElementById(tableId);

        if (!table) {
          return;
        }

        input.addEventListener("input", function () {
          var query = input.value.trim().toLowerCase();
          var rows = table.querySelectorAll("tbody tr");

          Array.prototype.forEach.call(rows, function (row) {
            row.hidden = query !== "" && row.textContent.toLowerCase().indexOf(query) === -1;
          });
        });
      });
    }

    function updateThemeControls(theme) {
      var nextTheme = theme === "dark" ? "light" : "dark";
      var label = "Switch to " + nextTheme + " mode";
      var iconClass = theme === "dark" ? "bi bi-sun" : "bi bi-moon-stars";

      Array.prototype.forEach.call(themeToggles, function (button) {
        button.setAttribute("aria-label", label);
        button.setAttribute("title", label);
      });

      Array.prototype.forEach.call(themeIcons, function (icon) {
        icon.className = iconClass;
      });
    }

    function applyTheme(theme) {
      document.documentElement.setAttribute("data-theme", theme);
      document.documentElement.setAttribute("data-bs-theme", theme);

      if (storageAvailable) {
        window.localStorage.setItem(themeStorageKey, theme);
      }

      updateThemeControls(theme);
    }

    function initThemeToggle() {
      applyTheme(getPreferredTheme(storageAvailable));

      Array.prototype.forEach.call(themeToggles, function (button) {
        button.addEventListener("click", function () {
          var currentTheme = document.documentElement.getAttribute("data-theme") === "dark" ? "dark" : "light";
          applyTheme(currentTheme === "dark" ? "light" : "dark");
        });
      });
    }

    function initGlobalSearch() {
      var searchInput = document.getElementById("globalSearchInput");
      var searchDropdown = document.getElementById("globalSearchDropdown");
      var searchResultsList = document.getElementById("globalSearchResultsList");
      var searchSpinner = document.getElementById("globalSearchSpinner");
      var searchContainer = document.querySelector(".global-search-container");

      if (!searchInput || !searchDropdown || !searchResultsList) {
        return;
      }

      var debounceTimer = null;
      var activeIndex = -1;
      var searchUrl = searchInput.getAttribute("data-search-url") || "/global-search";

      function escapeHtml(str) {
        if (!str) return "";
        return String(str)
          .replace(/&/g, "&amp;")
          .replace(/</g, "&lt;")
          .replace(/>/g, "&gt;")
          .replace(/"/g, "&quot;")
          .replace(/'/g, "&#039;");
      }

      function getStatusBadge(status, label) {
        var badgeClass = "bg-secondary-subtle text-secondary border border-secondary-subtle";
        var icon = "bi-circle-fill";

        if (status === "submitted") {
          badgeClass = "bg-primary-subtle text-primary border border-primary-subtle";
          icon = "bi-send-fill";
        } else if (status === "under_review") {
          badgeClass = "bg-warning-subtle text-warning-emphasis border border-warning-subtle";
          icon = "bi-hourglass-split";
        } else if (status === "accepted") {
          badgeClass = "bg-success-subtle text-success border border-success-subtle";
          icon = "bi-check-circle-fill";
        } else if (status === "rejected") {
          badgeClass = "bg-danger-subtle text-danger border border-danger-subtle";
          icon = "bi-x-circle-fill";
        } else if (status === "aktif") {
          badgeClass = "bg-info-subtle text-info-emphasis border border-info-subtle";
          icon = "bi-briefcase-fill";
        }

        return '<span class="badge ' + badgeClass + ' rounded-pill px-2.5 py-1 small fw-semibold d-inline-flex align-items-center gap-1"><i class="bi ' + icon + '" style="font-size: 0.65rem;"></i> ' + escapeHtml(label || status) + '</span>';
      }

      function renderResults(results, query) {
        searchResultsList.innerHTML = "";
        activeIndex = -1;

        if (!results || results.length === 0) {
          searchResultsList.innerHTML = '<div class="global-search-empty py-4 text-center">' +
            '<i class="bi bi-search fs-3 text-muted d-block mb-2"></i>' +
            '<div class="fw-semibold text-body mb-1">Tidak ada hasil ditemukan</div>' +
            '<div class="text-muted small">Tidak ada data yang cocok dengan "' + escapeHtml(query) + '"</div>' +
            '</div>';
          searchDropdown.classList.remove("d-none");
          return;
        }

        var registrations = results.filter(function(r) { return r.type === "registration"; });
        var positions = results.filter(function(r) { return r.type === "position"; });

        var html = '';

        if (registrations.length > 0) {
          html += '<div class="global-search-header text-uppercase px-3 py-1.5 small fw-bold text-muted border-bottom bg-body-tertiary">Pendaftaran Magang (' + registrations.length + ')</div>';
          registrations.forEach(function(item) {
            html += '<a href="' + escapeHtml(item.url) + '" class="global-search-item d-block px-3 py-2.5 text-decoration-none border-bottom">' +
              '<div class="d-flex align-items-center justify-content-between gap-2 mb-1">' +
                '<strong class="text-body fw-bold text-truncate" style="max-width: 260px;">' + escapeHtml(item.name) + '</strong>' +
                getStatusBadge(item.status, item.status_label) +
              '</div>' +
              '<div class="d-flex align-items-center justify-content-between gap-2 small text-muted">' +
                '<span class="font-monospace text-primary fw-semibold"><i class="bi bi-ticket-perforated me-1"></i>' + escapeHtml(item.code) + '</span>' +
                '<span class="text-truncate" style="max-width: 220px;"><i class="bi bi-briefcase me-1"></i>' + escapeHtml(item.position) + '</span>' +
              '</div>' +
            '</a>';
          });
        }

        if (positions.length > 0) {
          html += '<div class="global-search-header text-uppercase px-3 py-1.5 small fw-bold text-muted border-bottom bg-body-tertiary">Formasi Magang (' + positions.length + ')</div>';
          positions.forEach(function(item) {
            html += '<a href="' + escapeHtml(item.url) + '" class="global-search-item d-block px-3 py-2.5 text-decoration-none border-bottom">' +
              '<div class="d-flex align-items-center justify-content-between gap-2 mb-1">' +
                '<strong class="text-body fw-bold text-truncate"><i class="bi bi-briefcase-fill text-primary me-1.5"></i>' + escapeHtml(item.name) + '</strong>' +
                '<span class="badge bg-primary-subtle text-primary border rounded-pill px-2 py-0.5 small">' + escapeHtml(item.code) + '</span>' +
              '</div>' +
              '<div class="small text-muted">' + escapeHtml(item.position) + '</div>' +
            '</a>';
          });
        }

        searchResultsList.innerHTML = html;
        searchDropdown.classList.remove("d-none");
      }

      function performSearch(query) {
        if (!query || query.trim().length === 0) {
          searchDropdown.classList.add("d-none");
          if (searchSpinner) searchSpinner.classList.add("d-none");
          return;
        }

        if (searchSpinner) searchSpinner.classList.remove("d-none");

        fetch(searchUrl + "?q=" + encodeURIComponent(query.trim()), {
          headers: {
            "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest"
          }
        })
        .then(function(res) {
          if (!res.ok) throw new Error("Search request failed");
          return res.json();
        })
        .then(function(data) {
          if (searchSpinner) searchSpinner.classList.add("d-none");
          renderResults(data.results || [], query);
        })
        .catch(function() {
          if (searchSpinner) searchSpinner.classList.add("d-none");
          searchResultsList.innerHTML = '<div class="p-3 text-center text-danger small">Gagal memuat hasil pencarian.</div>';
          searchDropdown.classList.remove("d-none");
        });
      }

      searchInput.addEventListener("input", function() {
        var query = searchInput.value;
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function() {
          performSearch(query);
        }, 220);
      });

      searchInput.addEventListener("focus", function() {
        if (searchInput.value.trim().length > 0 && searchResultsList.children.length > 0) {
          searchDropdown.classList.remove("d-none");
        }
      });

      // Keyboard navigation
      searchInput.addEventListener("keydown", function(e) {
        var items = searchResultsList.querySelectorAll(".global-search-item");
        if (!items || items.length === 0) return;

        if (e.key === "ArrowDown") {
          e.preventDefault();
          activeIndex = (activeIndex + 1) % items.length;
          updateActiveItem(items);
        } else if (e.key === "ArrowUp") {
          e.preventDefault();
          activeIndex = (activeIndex - 1 + items.length) % items.length;
          updateActiveItem(items);
        } else if (e.key === "Enter") {
          if (activeIndex >= 0 && items[activeIndex]) {
            e.preventDefault();
            items[activeIndex].click();
          }
        } else if (e.key === "Escape") {
          searchDropdown.classList.add("d-none");
          activeIndex = -1;
        }
      });

      function updateActiveItem(items) {
        Array.prototype.forEach.call(items, function(el, idx) {
          if (idx === activeIndex) {
            el.classList.add("active");
            el.scrollIntoView({ block: "nearest" });
          } else {
            el.classList.remove("active");
          }
        });
      }

      // Close dropdown when clicking outside
      document.addEventListener("click", function(e) {
        if (searchContainer && !searchContainer.contains(e.target)) {
          searchDropdown.classList.add("d-none");
        }
      });
    }

    initValidation();
    initTableSearch();
    initThemeToggle();
    initGlobalSearch();

    // Initialize user profile values in UI. Provide a window.adminHMDUser object to override defaults.
    function initUserProfile() {
      var user = window.adminHMDUser || { name: "Admin Hasan", workspace: "Active Workspace", avatar: "../assets/images/avatar/avatar.jpg" };

      var sidebarNameEl = document.querySelector(".sidebar-user strong");
      var sidebarWorkspaceEl = document.querySelector(".sidebar-user small");
      var sidebarAvatar = document.querySelector(".sidebar-user .avatar-img");
      var profileNameEls = document.querySelectorAll(".profile-name");
      var profileAvatarEls = document.querySelectorAll(".profile-button .avatar-img, .profile-button img");

      if (sidebarNameEl) sidebarNameEl.textContent = user.name;
      if (sidebarWorkspaceEl) sidebarWorkspaceEl.textContent = user.workspace;
      if (sidebarAvatar && user.avatar) { sidebarAvatar.src = user.avatar; sidebarAvatar.alt = user.name; }

      Array.prototype.forEach.call(profileNameEls, function (el) { el.textContent = user.name; });
      Array.prototype.forEach.call(profileAvatarEls, function (img) { if (user.avatar) img.src = user.avatar; if (user.name) img.alt = user.name; });
    }

    initUserProfile();

    if (!sidebarToggle) {
      return;
    }

    function setClass(element, className, enabled) {
      if (enabled) {
        element.classList.add(className);
      } else {
        element.classList.remove(className);
      }
    }

    function setToggleExpanded() {
      var expanded = isDesktop()
        ? !body.classList.contains("sidebar-mini")
        : body.classList.contains("sidebar-open");

      sidebarToggle.setAttribute("aria-expanded", String(expanded));
    }

    function closeMobileSidebar() {
      body.classList.remove("sidebar-open");
      setToggleExpanded();
    }

    function toggleSidebar() {
      if (isDesktop()) {
        body.classList.toggle("sidebar-mini");
        saveMiniState(storageAvailable, body.classList.contains("sidebar-mini"));
      } else {
        body.classList.toggle("sidebar-open");
      }

      setToggleExpanded();
    }

    function addCloseHandlers(items) {
      Array.prototype.forEach.call(items, function (item) {
        item.addEventListener("click", function () {
          if (!isDesktop()) {
            closeMobileSidebar();
          }
        });
      });
    }

    if (getSavedMiniState(storageAvailable) && isDesktop()) {
      body.classList.add("sidebar-mini");
    }

    sidebarToggle.addEventListener("click", toggleSidebar);
    addCloseHandlers(closeButtons);
    addCloseHandlers(sidebarLinks);
    setToggleExpanded();

    function handleBreakpointChange() {
      if (isDesktop()) {
        body.classList.remove("sidebar-open");
        setClass(body, "sidebar-mini", getSavedMiniState(storageAvailable));
      } else {
        body.classList.remove("sidebar-mini");
      }

      setToggleExpanded();
    }

    if (mediaQuery.addEventListener) {
      mediaQuery.addEventListener("change", handleBreakpointChange);
    } else if (mediaQuery.addListener) {
      mediaQuery.addListener(handleBreakpointChange);
    }
  });
})();
