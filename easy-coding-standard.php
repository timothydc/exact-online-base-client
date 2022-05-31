<?php declare(strict_types=1);

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
use PhpCsFixer\Fixer\Whitespace\BlankLineBeforeStatementFixer;
use PhpCsFixer\Fixer\Whitespace\CompactNullableTypehintFixer;
use PhpCsFixer\Fixer\Whitespace\NoExtraBlankLinesFixer;
use PhpCsFixerCustomFixers\Fixer\NoImportFromGlobalNamespaceFixer;
use PhpCsFixerCustomFixers\Fixer\NoSuperfluousConcatenationFixer;
use PhpCsFixerCustomFixers\Fixer\NoUselessCommentFixer;
use PhpCsFixerCustomFixers\Fixer\NoUselessParenthesisFixer;
use PhpCsFixerCustomFixers\Fixer\NoUselessStrlenFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocNoIncorrectVarAnnotationFixer;
use PhpCsFixerCustomFixers\Fixer\SingleSpaceAfterStatementFixer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\CodingStandard\Fixer\ArrayNotation\ArrayListItemNewlineFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\ArrayOpenerAndCloserNewlineFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\StandaloneLineInMultilineArrayFixer;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

// for more CS STYLE rules look here:
// https://mlocati.github.io/php-cs-fixer-configurator/#version:3.5|fixer:class_attributes_separation
return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(SetList::SYMFONY);
    $containerConfigurator->import(SetList::SYMFONY_RISKY);
    $containerConfigurator->import(SetList::ARRAY);
    $containerConfigurator->import(SetList::CONTROL_STRUCTURES);
    $containerConfigurator->import(SetList::STRICT);
    $containerConfigurator->import(SetList::PSR_12);

    $services = $containerConfigurator->services();

    $services->set(ArraySyntaxFixer::class)->call('configure', [['syntax' => 'short']]);
    $services->set(BlankLineBeforeStatementFixer::class)->call('configure', [['statements' => ['break', 'continue', 'declare', 'return', 'throw', 'if']]]);
    $services->set(ClassAttributesSeparationFixer::class)->call('configure', [['elements' => ['property' => 'none', 'method' => 'one']]]);
    $services->set(CompactNullableTypehintFixer::class);
    $services->set(ConcatSpaceFixer::class)->call('configure', [['spacing' => 'one']]);
    $services->set(DeclareStrictTypesFixer::class);
    $services->set(FopenFlagsFixer::class);
    $services->set(GeneralPhpdocAnnotationRemoveFixer::class)->call('configure', [['annotations' => ['copyright', 'category']]]);
    $services->set(MethodArgumentSpaceFixer::class)->call('configure', [['on_multiline' => 'ensure_fully_multiline']]);
    $services->set(NativeConstantInvocationFixer::class);
    $services->set(NativeFunctionInvocationFixer::class);
    $services->set(NoExtraBlankLinesFixer::class)->call('configure', [['tokens' => ['case', 'continue', 'default', 'extra', 'parenthesis_brace_block', 'return', 'square_brace_block', 'switch', 'throw', 'use', 'use_trait']]]);
    $services->set(NoImportFromGlobalNamespaceFixer::class);
    $services->set(NoSuperfluousConcatenationFixer::class);
    $services->set(NoSuperfluousElseifFixer::class);
    $services->set(NoSuperfluousPhpdocTagsFixer::class)->call('configure', [['remove_inheritdoc' => true, 'allow_unused_params' => true]]);
    $services->set(NotOperatorWithSuccessorSpaceFixer::class);
    $services->set(NoTrailingCommaInSinglelineArrayFixer::class);
    $services->set(NoUselessCommentFixer::class);
    $services->set(NoUselessElseFixer::class);
    $services->set(NoUselessParenthesisFixer::class);
    $services->set(NoUselessReturnFixer::class);
    $services->set(NoUselessStrlenFixer::class);
    $services->set(NullableTypeDeclarationForDefaultNullValueFixer::class);
    $services->set(OperatorLinebreakFixer::class);
    $services->set(PhpdocAlignFixer::class)->call('configure', [['align' => 'left']]);
    $services->set(PhpdocLineSpanFixer::class);
    $services->set(PhpdocNoIncorrectVarAnnotationFixer::class);
    $services->set(PhpdocOrderFixer::class);
    $services->set(SelfAccessorFixer::class);
    $services->set(SingleSpaceAfterStatementFixer::class);
    $services->set(StrictParamFixer::class);
    $services->set(TrailingCommaInMultilineFixer::class);
    $services->set(VoidReturnFixer::class);

    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::PATHS, [
        'src',
    ]);

    $parameters->set(Option::SKIP, [
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
