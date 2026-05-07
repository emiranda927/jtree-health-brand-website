#!/usr/bin/env bash
# Seed the 12 JTree pages with their templates assigned, then configure
# reading settings + permalinks. Idempotent: skips pages that already exist
# at the target slug.
#
# Run from inside Local's site shell:
#   bash /Users/eliseo/Projects/jtree-health-website/site/jtree-wp-theme/scripts/seed-pages.sh

set -e

PAGES=(
  "Home|home|templates/page-home.php"
  "Programs|programs|templates/page-programs.php"
  "What We Treat|what-we-treat|templates/page-what-we-treat.php"
  "For Parents|for-parents|templates/page-for-parents.php"
  "For Teens|for-teens|templates/page-for-teens.php"
  "Insurance|insurance|templates/page-insurance.php"
  "Admissions|admissions|templates/page-admissions.php"
  "About|about|templates/page-about.php"
  "Contact|contact|templates/page-contact.php"
  "Thank You|thank-you|templates/page-thank-you.php"
  "Crisis Resources|crisis|templates/page-crisis.php"
  "Privacy Policy|privacy|templates/page-privacy.php"
)

echo "Seeding 12 JTree pages…"
for entry in "${PAGES[@]}"; do
  IFS='|' read -r title slug template <<< "$entry"
  existing=$(wp post list --post_type=page --name="$slug" --field=ID 2>/dev/null || true)
  if [ -z "$existing" ]; then
    wp post create \
      --post_type=page \
      --post_title="$title" \
      --post_name="$slug" \
      --post_status=publish \
      --page_template="$template"
  else
    echo "  → skipped (exists): /$slug/ (ID $existing)"
  fi
done

echo ""
echo "Setting Home as the static front page…"
home_id=$(wp post list --post_type=page --name=home --field=ID)
wp option update show_on_front page
wp option update page_on_front "$home_id"

echo "Setting permalinks to /%postname%/…"
wp rewrite structure '/%postname%/' --hard
wp rewrite flush --hard

echo ""
echo "Done. Visit:"
wp option get home
