<?php

declare(strict_types=1);

use PHP_CodeSniffer\Standards\Generic\Sniffs\CodeAnalysis\AssignmentInConditionSniff;
use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\ArrayNotation\NoTrailingCommaInSinglelineArrayFixer;
use PhpCsFixer\Fixer\ClassNotation\ClassAttributesSeparationFixer;
use PhpCsFixer\Fixer\ClassNotation\ProtectedToPrivateFixer;
use PhpCsFixer\Fixer\ClassNotation\SelfAccessorFixer;
use PhpCsFixer\Fixer\ConstantNotation\NativeConstantInvocationFixer;
use PhpCsFixer\Fixer\ControlStructure\NoSuperfluousElseifFixer;
use PhpCsFixer\Fixer\ControlStructure\NoTrailingCommaInListCallFixer;
use PhpCsFixer\Fixer\ControlStructure\NoUselessElseFixer;
use PhpCsFixer\Fixer\ControlStructure\TrailingCommaInMultilineFixer;
use PhpCsFixer\Fixer\ControlStructure\YodaStyleFixer;
use PhpCsFixer\Fixer\FunctionNotation\FopenFlagsFixer;
use PhpCsFixer\Fixer\FunctionNotation\MethodArgumentSpaceFixer;
use PhpCsFixer\Fixer\FunctionNotation\NativeFunctionInvocationFixer;
use PhpCsFixer\Fixer\FunctionNotation\NullableTypeDeclarationForDefaultNullValueFixer;
use PhpCsFixer\Fixer\FunctionNotation\SingleLineThrowFixer;
use PhpCsFixer\Fixer\FunctionNotation\VoidReturnFixer;
use PhpCsFixer\Fixer\LanguageConstruct\ExplicitIndirectVariableFixer;
use PhpCsFixer\Fixer\Operator\ConcatSpaceFixer;
use PhpCsFixer\Fixer\Operator\NotOperatorWithSuccessorSpaceFixer;
use PhpCsFixer\Fixer\Operator\OperatorLinebreakFixer;
use PhpCsFixer\Fixer\Phpdoc\GeneralPhpdocAnnotationRemoveFixer;
use PhpCsFixer\Fixer\Phpdoc\NoSuperfluousPhpdocTagsFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocAlignFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocLineSpanFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocOrderFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocSummaryFixer;
use PhpCsFixer\Fixer\PhpTag\BlankLineAfterOpeningTagFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitFqcnAnnotationFixer;
use PhpCsFixer\Fixer\ReturnNotation\NoUselessReturnFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use PhpCsFixer\Fixer\Strict\StrictParamFixer;
use PhpCsFixer\Fixer\StringNotation\ExplicitStringVariableFixer;
use PhpCsFixer\Fixer\StringNotation\StringLengthToEmptyFixer;
use PhpCsFixer\Fixer\Whitespace\BlankLineBeforeStatementFixer;
use PhpCsFixer\Fixer\Whitespace\CompactNullableTypehintFixer;
use PhpCsFixer\Fixer\Whitespace\NoExtraBlankLinesFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\ArrayListItemNewlineFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\ArrayOpenerAndCloserNewlineFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\StandaloneLineInMultilineArrayFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

// for more CS STYLE rules look here:
// https://mlocati.github.io/php-cs-fixer-configurator
return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([__DIR__ . '/src']);

    $ecsConfig->sets([
        SetList::ARRAY,
        SetList::CONTROL_STRUCTURES,
        SetList::STRICT,
        SetList::PSR_12,
    ]);

    $ecsConfig->ruleWithConfiguration(ArraySyntaxFixer::class, ['syntax' => 'short']);
    $ecsConfig->ruleWithConfiguration(BlankLineBeforeStatementFixer::class, ['statements' => ['break', 'continue', 'declare', 'return', 'throw', 'if']]);
    $ecsConfig->ruleWithConfiguration(ClassAttributesSeparationFixer::class, ['elements' => ['property' => 'none', 'method' => 'one']]);
    $ecsConfig->ruleWithConfiguration(ConcatSpaceFixer::class, ['spacing' => 'one']);
    $ecsConfig->rule(CompactNullableTypehintFixer::class);
    $ecsConfig->rule(DeclareStrictTypesFixer::class);
    $ecsConfig->rule(FopenFlagsFixer::class);
    $ecsConfig->ruleWithConfiguration(GeneralPhpdocAnnotationRemoveFixer::class, ['annotations' => ['copyright', 'category']]);
    $ecsConfig->ruleWithConfiguration(MethodArgumentSpaceFixer::class, ['on_multiline' => 'ensure_fully_multiline']);
    $ecsConfig->rule(NativeConstantInvocationFixer::class);
    $ecsConfig->rule(NativeFunctionInvocationFixer::class);
    $ecsConfig->ruleWithConfiguration(NoExtraBlankLinesFixer::class, ['tokens' => ['case', 'continue', 'default', 'extra', 'parenthesis_brace_block', 'return', 'square_brace_block', 'switch', 'throw', 'use', 'use_trait']]);
    $ecsConfig->rule(NoSuperfluousElseifFixer::class);
    $ecsConfig->ruleWithConfiguration(NoSuperfluousPhpdocTagsFixer::class, ['remove_inheritdoc' => true, 'allow_unused_params' => true]);
    $ecsConfig->rule(NotOperatorWithSuccessorSpaceFixer::class);
    $ecsConfig->rule(NoTrailingCommaInSinglelineArrayFixer::class);

    $ecsConfig->rule(NoUselessElseFixer::class);
    $ecsConfig->rule(NoUselessReturnFixer::class);
    $ecsConfig->rule(StringLengthToEmptyFixer::class);
    $ecsConfig->rule(NullableTypeDeclarationForDefaultNullValueFixer::class);
    $ecsConfig->rule(OperatorLinebreakFixer::class);
    $ecsConfig->ruleWithConfiguration(PhpdocAlignFixer::class, ['align' => 'left']);
    $ecsConfig->rule(PhpdocLineSpanFixer::class);
    $ecsConfig->rule(PhpdocOrderFixer::class);
    $ecsConfig->rule(SelfAccessorFixer::class);
    $ecsConfig->rule(StrictParamFixer::class);
    $ecsConfig->rule(TrailingCommaInMultilineFixer::class);
    $ecsConfig->rule(VoidReturnFixer::class);

    $ecsConfig->skip([
        AssignmentInConditionSniff::class => null,
        ArrayListItemNewlineFixer::class => null,
        ArrayOpenerAndCloserNewlineFixer::class => null,
        BlankLineAfterOpeningTagFixer::class => null,
        ExplicitIndirectVariableFixer::class => null,
        ExplicitStringVariableFixer::class => null,
        NoTrailingCommaInListCallFixer::class => null,
        NoTrailingCommaInSinglelineArrayFixer::class => null,
        PhpdocSummaryFixer::class => null,
        PhpUnitFqcnAnnotationFixer::class => null,
        ProtectedToPrivateFixer::class => null,
        SingleLineThrowFixer::class => null,
        StandaloneLineInMultilineArrayFixer::class => null,
        YodaStyleFixer::class => null,
    ]);
};