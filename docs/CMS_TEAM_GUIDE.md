# Joshua Tree Health Website Editing Guide

Pages CMS gives the founder and clinical team a form-based way to update approved website copy. It saves changes to GitHub, where the existing site checks and Vercel previews run before anything is published.

## What is editable in the pilot

- Homepage copy, including the opening, program overview, process, and closing invitation
- About page copy, including the founder section, history, values, and program facts
- Team page names, roles, credentials, section copy, and calls to action
- Learning Hub index copy and the descriptions of its four current resources
- Page titles and search descriptions for those four pages

The editor cannot change page layouts, navigation behavior, GSAP or Lenis motion, form fields, analytics, API URLs, redirects, canonical rules, structured-data identifiers, crisis contact information, phone links, or destination URLs.

## Administrator setup

1. Go to [app.pagescms.org](https://app.pagescms.org/) and sign in with a GitHub administrator account whose repository access you are comfortable authorizing for Pages CMS. Hosted GitHub sign-in requests repository access.
2. Install the Pages CMS GitHub App for `emiranda927/jtree-health-brand-website` only. Do not grant it access to every repository.
3. Open the repository in Pages CMS.
4. Select the `content/drafts` branch. Do not edit website copy on `main`.
5. Open the collaborator settings and invite the founder and Clinical Director by email. They should use the email invitation rather than authorizing personal GitHub accounts.
6. Send each person this guide and confirm that they can open **Website copy pilot**.

If the repository or fields do not appear, confirm that Pages CMS is on `content/drafts` and refresh. The editor configuration lives in `.pages.yml` on that branch.

## Editing copy

1. Sign in to Pages CMS.
2. Confirm the selected branch is `content/drafts`.
3. Open **Website copy pilot**.
4. Choose Homepage, About Joshua Tree Health, Team, or Learning Hub.
5. Edit the labeled fields.
6. Save the page. Saving creates a Git commit under the Pages CMS app identity on the draft branch; it does not expose the editor's email in the public Git history and does not change the production website.
7. Wait for the Vercel preview deployment to finish, then review the rendered page before requesting publication.

Required fields cannot be left empty. Search titles and descriptions have practical length limits. Verified homepage facts and Learning Hub destinations are visible but locked.

## Reviewing the visual result

Every save to `content/drafts` creates or updates a Vercel preview deployment. Open the latest preview from the repository's deployment status or Vercel's **Deployments** page.

Review at least:

- The edited page on desktop and mobile
- Headline wrapping and button labels
- Program schedules and insurance wording
- Team names, roles, licenses, and credentials
- Internal links and the admissions call to action

Vercel preview URLs are automatically marked `noindex`, so drafts should not appear in search results.

## Requesting publication

1. Save the draft in Pages CMS and wait for its Vercel preview deployment.
2. Send the preview URL and a short description of the change to the website administrator.
3. The administrator opens a GitHub pull request from `content/drafts` to `main`.
4. Review the Vercel preview attached to that pull request.
5. Obtain the required content approval.
6. Confirm `npm run verify` passes locally or in the technical review.
7. Merge the pull request. The merge to `main` triggers the existing production deployment.

Merging is the publishing action. Closing Pages CMS or saving a draft is not.

After a publication is merged, an administrator should update `content/drafts` from `main` before the next editing round so the draft branch includes the latest website code and copy.

## Who approves what

### Founder approval

- Brand voice and founder story
- Mission, values, history, and rebrand language
- Major homepage headlines and positioning

### Clinical Director approval

- Diagnoses, symptoms, suitability, inclusion, and exclusion language
- PHP and IOP schedules, intensity, services, and treatment methods
- Family participation, psychiatric, safety, outcomes, and insurance claims
- Every clinician name, role, license, credential, and biography

### Technical check

- The Astro build and SEO audit pass with `npm run verify`
- The preview has no broken layout, missing content, or broken links
- Forms, analytics, redirects, and structured data were not changed

Use [CLAIM_REGISTER.md](./CLAIM_REGISTER.md) as the source of truth for operational and clinical claims. If a proposed statement is not supported there, verify it before publishing rather than strengthening the wording.

## Writing and privacy rules

- Write for parents and clinicians, not search engines.
- Preserve the brand voice and avoid keyword repetition.
- Do not add testimonials without documented consent.
- Never enter patient names, stories, ages, messages, insurance details, or protected health information into Pages CMS.
- Do not promise response times, admission dates, outcomes, availability, or coverage unless operations has verified them.
- Avoid publishing a clinician credential until that clinician has confirmed it.

## Undoing a published change

Every Pages CMS save is a Git commit. To undo a change, revert the relevant commit or pull request and merge the revert into `main`. Vercel will publish the restored version. The CMS is not connected to the admissions API, lead sheet, email delivery, or CRM.

## Getting help

When reporting a problem, include:

- The page name
- The draft branch name
- The Vercel preview URL
- A screenshot of the field or layout problem
- Whether the Vercel preview built successfully
