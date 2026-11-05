# PHPMD Violations Report
**Blog and Comment Modules - June 18, 2026**

---

## BLOG MODULE

### Violations Summary
- **Total violations**: 109
- **Files affected**: 24
- **Severity categories**: 8

### By Violation Type

#### CamelCaseVariableName (13 violations)
- `Models/Article.php:397` - $user_class
- `Models/Article.php:471` - $current_article
- `Models/Article.php:622` - $name_blocks
- `Models/Article.php:644` - $name_blocks
- `Models/Article.php:729` - $profile_id
- `Models/Comment.php:117` - $user_class
- `Models/Transaction.php:94` - $rating_morph
- `Filament/Resources/ArticleResource/Pages/EditArticle.php:38` - $article_resource
- `Http/Livewire/Article/Chunk.php:32` - $view_params
- `Http/Livewire/Article/Lists.php:66` - $view_params
- `Http/Livewire/Headernav/SearchBar.php:31` - $view_params
- `Http/Livewire/Profile.php:62` - $view_params
- `Http/Livewire/Profile/Setting.php:65` - $view_params
- `View/Components/Article/Card.php:33` - $view_params
- `View/Components/Article/Footer.php:43` - $view_params
- `View/Components/Article/Meta.php:32` - $view_params
- `View/Components/Menu.php:33` - $view_params
- `View/Composers/ThemeComposer.php:104` - $category_id
- `View/Composers/ThemeComposer.php:117` - $category_id
- `View/Composers/ThemeComposer.php:233` - $slider_data
- `database/seeders/ArticleSeeder.php:68` - $category_key
- `Providers/BlogServiceProvider.php:9` - $module_dir, $module_ns (CamelCasePropertyName)
- `Providers/RouteServiceProvider.php:9` - $module_dir, $module_ns (CamelCasePropertyName)

#### CamelCaseParameterName (22 violations)
- `DataObjects/ArticleData.php:15` - $bet_end_date, $event_start_date, $event_end_date, $status_display, $is_wagerable, $brier_score, $brier_score_play_money, $brier_score_real_money, $wagers_count, $wagers_count_canonical, $wagers_count_total, $volume_play_money, $volume_real_money, $thumbnail_2x
- `Datas/ArticleData.php:32` - $category_id, $show_on_homepage, $published_at, $content_blocks, $sidebar_blocks, $footer_blocks, $closed_at, $closed_at_date, $time_left_for_humans
- `Models/Article.php:469` - $current_article
- `Models/Article.php:616` - $name_blocks
- `Models/Article.php:637` - $name_blocks
- `Models/Article.php:727` - $profile_id
- `View/Composers/ThemeComposer.php:102` - $category_id
- `View/Composers/ThemeComposer.php:115` - $category_id

#### ExcessiveParameterList (2 violations)
- `DataObjects/ArticleData.php:15` - __construct has 20 parameters
- `Datas/ArticleData.php:32` - __construct has 18 parameters

#### UnusedFormalParameter (11 violations)
- `Filament/Blocks/BannerAndSlides.php:15` - $context
- `Filament/Blocks/DropdownMenu.php:16` - $context
- `Filament/Fields/ArticleContent.php:19` - $context
- `Filament/Fields/ArticleSidebar.php:19` - $context
- `Http/Livewire/Article/Chunk.php:44` - $name, $params
- `Http/Livewire/Profile.php:72` - $name, $params
- `Http/Livewire/Profile/Setting.php:92` - $record, $arguments
- `Http/Livewire/Profile/Setting.php:160` - $record
- `Http/Livewire/Profile/Setting.php:191` - $arguments, $livewire
- `Models/Article.php:452` - $value
- `Models/Article.php:820` - $value
- `Models/Banner.php:160` - $media
- `Models/BaseTreeModel.php:36` - $parent
- `Models/Concerns/HasPathByParentId.php:115` - $value

#### BooleanArgumentFlag (4 violations)
- `DataObjects/ArticleData.php:29` - $is_wagerable
- `Models/Article.php:326` - $useFallbackLocale
- `View/Components/Article/Card.php:21` - $showAuthor

#### ExcessiveClassComplexity (2 violations)
- `Models/Article.php:230` - complexity: 58 (threshold: 50)

#### CyclomaticComplexity (2 violations)
- `Console/Commands/ShowArticleCommand.php:29` - Cyclomatic Complexity: 10
- `DataObjects/ArticleData.php:53` - Cyclomatic Complexity: 26

#### NPathComplexity (2 violations)
- `Console/Commands/ShowArticleCommand.php:29` - NPath complexity: 260 (threshold: 200)
- `DataObjects/ArticleData.php:53` - fromArray() method

#### ElseExpression (5 violations)
- `Actions/ParentChilds/GetTreeOptions.php:53` - execute() method
- `Console/Commands/ShowArticleListCommand.php:39` - handle() method
- `Models/Article.php:340` - getTranslation() method

#### LongVariable (3 violations)
- `DataObjects/ArticleData.php:31` - $brier_score_play_money
- `DataObjects/ArticleData.php:32` - $brier_score_real_money
- `DataObjects/ArticleData.php:34` - $wagers_count_canonical

#### CouplingBetweenObjects (5 violations)
- `Filament/Resources/ArticleResource.php:26` - coupling value: 16
- `Filament/Resources/ArticleResource/Pages/ListArticles.php:25` - coupling value: 13
- `Filament/Resources/ArticleResource/Schemas/ArticleForm.php:25` - coupling value: 15
- `Filament/Resources/CategoryResource.php:23` - coupling value: 13
- `View/Composers/ThemeComposer.php:25` - coupling value: 17

#### ExcessiveMethodLength (2 violations)
- `Filament/Resources/ArticleResource.php:46` - getFormFields() has 147 lines
- `Filament/Resources/ArticleResource/Schemas/ArticleForm.php:30` - getFormSchema() has 147 lines

#### IfStatementAssignment (1 violation)
- `Http/Livewire/Article/Lists.php:97` - Assignment in if clause

#### MissingImport (1 violation)
- `Enums/ArticleStatus.php:33` - Missing class import

#### ShortVariable (2 violations)
- `tests/Unit/SumTest.php:7` - $a, $b

---

## COMMENT MODULE

### Violations Summary
- **Total violations**: 124
- **Files affected**: 17
- **Severity categories**: 10

### By Violation Type

#### BooleanArgumentFlag (18 violations)
- `Filament/Widgets/Commentable/CommentsWidget.php:61` - $readOnly
- `Filament/Widgets/Commentable/CommentsWidget.php:63` - $hideNotificationOptions
- `Filament/Widgets/Commentable/CommentsWidget.php:64` - $noReplies
- `Filament/Widgets/Commentable/CommentsWidget.php:65` - $noReactions
- `Filament/Widgets/Commentable/CommentsWidget.php:66` - $newestFirst
- `Http/Livewire/CommentComponent.php:39` - $showAvatar
- `Http/Livewire/CommentComponent.php:40` - $newestFirst
- `Http/Livewire/CommentComponent.php:41` - $writable
- `Http/Livewire/CommentComponent.php:42` - $showReplies
- `Http/Livewire/CommentComponent.php:43` - $showReactions
- `Http/Livewire/CommentsComponent.php:53` - $readOnly
- `Http/Livewire/CommentsComponent.php:55` - $hideNotificationOptions
- `Http/Livewire/CommentsComponent.php:56` - $newestFirst
- `Http/Livewire/CommentsComponent.php:57` - $noReplies
- `Http/Livewire/CommentsComponent.php:58` - $noReactions
- `vendor/composer/ClassLoader.php:180` - $prepend
- `vendor/composer/ClassLoader.php:230` - $prepend
- `vendor/composer/ClassLoader.php:387` - $prepend

#### ExcessiveClassComplexity (4 violations)
- `Filament/Widgets/Commentable/CommentsWidget.php:24` - complexity: 55 (threshold: 50)
- `Models/Comment.php:61` - complexity: 82 (threshold: 50)
- `Support/CommentConfig.php:24` - complexity: 57 (threshold: 50)
- `vendor/composer/ClassLoader.php:43` - complexity: 71 (threshold: 50)

#### MissingImport (6 violations)
- `Filament/Widgets/Commentable/CommentsWidget.php:273` - Missing class import
- `Http/Livewire/CommentsComponent.php:179` - Missing class import
- `Jobs/SendNotificationsForApprovedCommentJob.php:56` - Missing class import
- `Models/Comment.php:505` - Missing class import
- `Models/Concerns/HasComments.php:50` - Missing class import
- `Support/CommentConfig.php:321` - Missing class import
- `Support/CommentConfig.php:335` - Missing class import
- `vendor/composer/ClassLoader.php:250` - Missing class import
- `vendor/composer/ClassLoader.php:305` - Missing class import
- `vendor/composer/autoload_real.php:28` - Missing class import

#### UnusedFormalParameter (15 violations)
- `Models/Collections/ReactionCollection.php:42` - $key
- `Notifications/ApprovedCommentNotification.php:22` - $notifiable
- `Notifications/ApprovedCommentNotification.php:27` - $notifiable
- `Notifications/PendingCommentNotification.php:24` - $notifiable
- `Notifications/PendingCommentNotification.php:29` - $notifiable
- `Policies/CommentPolicy.php:44` - $user, $commentableModel
- `database/factories/CommentFactory.php:55` - $attributes
- `database/factories/CommentFactory.php:65` - $attributes
- `database/factories/CommentFactory.php:75` - $attributes
- `packages/spatie/laravel-comments-livewire/src/Policies/CommentPolicy.php:45` - $user, $commentableModel
- `vendor/composer/ClassLoader.php:452` - UndefinedVariable ($hit - twice)

#### LongVariable (6 violations)
- `Filament/Widgets/Commentable/CommentsWidget.php:63` - $hideNotificationOptions
- `Http/Livewire/CommentsComponent.php:33` - $showNotificationOptions
- `Http/Livewire/CommentsComponent.php:41` - $selectedNotificationSubscriptionType
- `Http/Livewire/CommentsComponent.php:55` - $hideNotificationOptions
- `Jobs/SendNotificationsForApprovedCommentJob.php:71` - $subscribingToParticipating
- `Jobs/SendNotificationsForApprovedCommentJob.php:72` - $participatingCommentators
- `Models/Concerns/InteractsWithComments.php:60` - $notificationSubscription (appears twice)
- `tests/TestCase.php:17` - $connectionsToTransact
- `vendor/composer/ClassLoader.php:88` - $classMapAuthoritative
- `vendor/composer/ClassLoader.php:343` - $classMapAuthoritative

#### CyclomaticComplexity (4 violations)
- `Filament/Widgets/Commentable/CommentsWidget.php:57` - mount() has complexity: 15
- `Filament/Widgets/Commentable/CommentsWidget.php:139` - insertMention() has complexity: 10
- `Models/Comment.php:430` - getApprovingUsers() has complexity: 10
- `vendor/composer/ClassLoader.php:442` - findFile() has complexity: 10
- `vendor/composer/ClassLoader.php:492` - findFileWithExtension() has complexity: 18

#### NPathComplexity (3 violations)
- `Filament/Widgets/Commentable/CommentsWidget.php:57` - mount() has NPath complexity: 900 (threshold: 200)
- `vendor/composer/ClassLoader.php:442` - findFile() has NPath complexity: 216 (threshold: 200)
- `vendor/composer/ClassLoader.php:492` - findFileWithExtension() has NPath complexity: 1944 (threshold: 200)

#### CamelCasePropertyName (4 violations)
- `Providers/CommentServiceProvider.php:18` - $module_dir, $module_ns
- `Providers/RouteServiceProvider.php:9` - $module_dir, $module_ns

#### ElseExpression (9 violations)
- `Filament/Widgets/Commentable/CommentsWidget.php:86` - mount() method
- `Filament/Widgets/Commentable/CommentsWidget.php:95` - mount() method
- `Filament/Widgets/Commentable/CommentsWidget.php:177` - comment() method
- `Http/Livewire/CommentComponent.php:150` - toggleReaction() method
- `Http/Livewire/CommentsComponent.php:88` - comment() method
- `vendor/composer/ClassLoader.php:165` - addClassMap() method
- `vendor/composer/ClassLoader.php:189` - add() method
- `vendor/composer/ClassLoader.php:210` - add() method
- `vendor/composer/ClassLoader.php:240` - addPsr4() method
- `vendor/composer/ClassLoader.php:260` - addPsr4() method
- `vendor/composer/ClassLoader.php:282` - set() method
- `vendor/composer/ClassLoader.php:302` - setPsr4() method
- `vendor/composer/ClassLoader.php:397` - register() method
- `vendor/composer/ClassLoader.php:526` - findFileWithExtension() method

#### CouplingBetweenObjects (3 violations)
- `Models/Comment.php:61` - coupling value: 24
- `Support/CommentConfig.php:24` - coupling value: 16

#### ShortVariable (2 violations)
- `Filament/Widgets/Commentable/CommentsWidget.php:39` - $ui
- `Filament/Widgets/Commentable/CommentsWidget.php:68` - $ui
- `Filament/Widgets/Mention/MentionSearchWidget.php:21` - $ui

#### ExcessiveParameterList (1 violation)
- `Filament/Widgets/Commentable/CommentsWidget.php:57` - mount() has 11 parameters

#### TooManyMethods (1 violation)
- `Support/CommentConfig.php:24` - 38 non-getter/setter methods (threshold: 30)

#### LongClassName (2 violations)
- `vendor/composer/autoload_real.php:5` - ComposerAutoloaderInit8a366c3e9ae1668a2f12454021ed39ae
- `vendor/composer/autoload_static.php:7` - ComposerStaticInit8a366c3e9ae1668a2f12454021ed39ae

#### BooleanGetMethodName (1 violation)
- `vendor/composer/ClassLoader.php:330` - getUseIncludePath() should be named is...() or has...()

#### IfStatementAssignment (3 violations)
- `vendor/composer/ClassLoader.php:423` - Assignment in if clause
- `vendor/composer/ClassLoader.php:492` - Multiple assignments in if clauses (6 instances)

#### UndefinedVariable (2 violations)
- `vendor/composer/ClassLoader.php:452` - $hit variable used without definition (twice)

---

## Summary Statistics

### Blog Module
| Metric | Count |
|--------|-------|
| Total Violations | 109 |
| Files Affected | 24 |
| Critical Issues | ExcessiveParameterList (2), ExcessiveClassComplexity (2) |
| Most Common | CamelCaseVariableName (13), CamelCaseParameterName (22) |

### Comment Module
| Metric | Count |
|--------|-------|
| Total Violations | 124 |
| Files Affected | 17 |
| Critical Issues | ExcessiveClassComplexity (4), BooleanArgumentFlag (18) |
| Most Common | BooleanArgumentFlag (18), UnusedFormalParameter (15) |

### Key Findings
1. **Naming Convention Issues**: Both modules have significant naming convention violations (snake_case vs camelCase)
2. **Class Complexity**: Both modules have classes exceeding the complexity threshold (Blog: Article, Comment: Comment, CommentConfig)
3. **Boolean Flags**: Comment module extensively uses boolean flag arguments (antipattern indicating SRP violation)
4. **Missing Imports**: Both modules have unresolved import statements
5. **Long Variables**: Long variable names and parameter names in critical data transfer objects (ArticleData, CommentsComponent)
6. **Vendor Code**: Comment module includes violations in vendor/composer (these can typically be excluded)

